<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Invests\Models\Invest;
use FI\Modules\Invests\Requests\InvestStoreRequest;
use FI\Support\DateFormatter;

class InvestCreateController extends Controller
{
    public function create()
    {
        return view('invests._modal_create')
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('groups', Group::getList());
    }

    public function store(InvestStoreRequest $request)
    {
        $input = $request->except('client_name');

        $input['client_id'] = Client::firstOrCreateByUniqueName($request->input('client_name'))->id;
        $input['invest_date'] = DateFormatter::unformat($input['invest_date']);

        $invest = Invest::create($input);

        return response()->json(['id' => $invest->id], 200);
    }
}