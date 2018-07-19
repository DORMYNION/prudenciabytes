<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\RecurringLoans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\RecurringLoans\Models\RecurringLoan;
use FI\Support\Frequency;
use FI\Traits\ReturnUrl;

class RecurringLoanController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();

        $status = request('status', 'all_statuses');

        $recurringLoans = RecurringLoan::select('recurring_loans.*')
            ->join('clients', 'clients.id', '=', 'recurring_loans.client_id')
            ->join('recurring_loan_amounts', 'recurring_loan_amounts.recurring_loan_id', '=', 'recurring_loans.id')
            ->with(['client', 'activities', 'amount.recurringLoan.currency'])
            ->keywords(request('search'))
            ->clientId(request('client'))
            ->status($status)
            ->companyProfileId(request('company_profile'))
            ->sortable(['next_date' => 'desc', 'id' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return view('recurring_loans.index')
            ->with('recurringLoans', $recurringLoans)
            ->with('displaySearch', true)
            ->with('frequencies', Frequency::lists())
            ->with('status', $status)
            ->with('statuses', ['all_statuses' => trans('fi.all_statuses'), 'active' => trans('fi.active'), 'inactive' => trans('fi.inactive')])
            ->with('companyProfiles', ['' => trans('fi.all_company_profiles')] + CompanyProfile::getList());
    }

    public function delete($id)
    {
        RecurringLoan::destroy($id);

        return redirect()->route('recurringLoans.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }
}