<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Invests\Models\Invest;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\InvestStatuses;
use FI\Traits\ReturnUrl;

class InvestController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();

        $status = request('status', 'all_statuses');

        $invests = Invest::select('invests.*')
            ->join('clients', 'clients.id', '=', 'invests.client_id')
            ->join('invest_amounts', 'invest_amounts.invest_id', '=', 'invests.id')
            ->with(['client', 'activities', 'amount.invest.currency'])
            ->status($status)
            ->keywords(request('search'))
            ->clientId(request('client'))
            ->companyProfileId(request('company_profile'))
            ->sortable(['invest_date' => 'desc', 'LENGTH(number)' => 'desc', 'number' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return view('invests.index')
            ->with('invests', $invests)
            ->with('status', $status)
            ->with('statuses', InvestStatuses::listsAllFlat())
            ->with('keyedStatuses', InvestStatuses::lists())
            ->with('companyProfiles', ['' => trans('fi.all_company_profiles')] + CompanyProfile::getList())
            ->with('displaySearch', true);
    }

    public function delete($id)
    {
        Invest::destroy($id);

        return redirect()->route('invests.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function bulkDelete()
    {
        Invest::destroy(request('ids'));
    }

    public function bulkStatus()
    {
        Invest::whereIn('id', request('ids'))->update(['invest_status_id' => request('status')]);
    }

    public function pdf($id)
    {
        $invest = Invest::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($invest->html, FileNames::invest($invest));
    }
}