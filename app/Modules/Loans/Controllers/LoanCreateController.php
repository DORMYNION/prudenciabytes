<?php


namespace FI\Modules\Loans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Requests\LoanStoreRequest;
use FI\Support\DateFormatter;

class LoanCreateController extends Controller
{
    public function create()
    {
        return view('loans._modal_create')
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('groups', Group::getList());
    }

    public function store(LoanStoreRequest $request)
    {
        $input = $request->except('client_name');

        $input['client_id'] = Client::firstOrCreateByUniqueName($request->input('client_name'))->id;
        $input['loan_date'] = DateFormatter::unformat($input['loan_date']);

        $loan = Loan::create($input);

        return response()->json(['id' => $loan->id], 200);
    }
}
