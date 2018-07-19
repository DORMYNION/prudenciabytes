<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\RecurringLoans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\RecurringLoans\Models\RecurringLoan;
use FI\Modules\RecurringLoans\Requests\RecurringLoanStoreRequest;
use FI\Support\DateFormatter;
use FI\Support\Frequency;

class RecurringLoanCreateController extends Controller
{
    public function create()
    {
        return view('recurring_loans._modal_create')
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('groups', Group::getList())
            ->with('frequencies', Frequency::lists());
    }

    public function store(RecurringLoanStoreRequest $request)
    {
        $input = $request->except('client_name');

        $input['client_id'] = Client::firstOrCreateByUniqueName($request->input('client_name'))->id;
        $input['next_date'] = DateFormatter::unformat($input['next_date']);
        $input['stop_date'] = ($input['stop_date']) ? DateFormatter::unformat($input['stop_date']) : '0000-00-00';

        $recurringLoan = RecurringLoan::create($input);

        return response()->json(['success' => true, 'url' => route('recurringLoans.edit', [$recurringLoan->id])], 200);
    }
}