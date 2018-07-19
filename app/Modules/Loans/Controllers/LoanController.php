<?php


namespace FI\Modules\Loans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Loans\Models\Loan;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\LoanStatuses;
use FI\Traits\ReturnUrl;

class LoanController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();

        $status = request('status', 'all_statuses');

        $loans = Loan::select('loans.*')
            ->join('clients', 'clients.id', '=', 'loans.client_id')
            ->join('loan_amounts', 'loan_amounts.loan_id', '=', 'loans.id')
            ->with(['client', 'activities', 'amount.loan.currency'])
            ->status($status)
            ->keywords(request('search'))
            ->clientId(request('client'))
            ->companyProfileId(request('company_profile'))
            ->sortable(['loan_date' => 'desc', 'LENGTH(number)' => 'desc', 'number' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return view('loans.index')
            ->with('loans', $loans)
            ->with('status', $status)
            ->with('statuses', LoanStatuses::listsAllFlat() + ['overdue' => trans('fi.overdue')])
            ->with('keyedStatuses', collect(LoanStatuses::lists())->except(3))
            ->with('companyProfiles', ['' => trans('fi.all_company_profiles')] + CompanyProfile::getList())
            ->with('displaySearch', true);
    }

    public function form()  {
        return view('loans.form');
    }


    public function delete($id)
    {
        Loan::destroy($id);

        return redirect()->route('loans.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function bulkDelete()
    {
        Loan::destroy(request('ids'));
    }

    public function bulkStatus()
    {
        Loan::whereIn('id', request('ids'))
            ->where('loan_status_id', '<>', LoanStatuses::getStatusId('paid'))
            ->update(['loan_status_id' => request('status')]);
    }

    public function pdf($id)
    {
        $loan = Loan::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($loan->html, FileNames::loan($loan));
    }
}
