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
use FI\Modules\Invests\Models\InvestItem;
use FI\Modules\Invests\Requests\InvestStoreRequest;
use FI\Support\DateFormatter;

class InvestCopyController extends Controller
{
    public function create()
    {
        $invest = Invest::find(request('invest_id'));

        return view('invests._modal_copy')
            ->with('invest', $invest)
            ->with('groups', Group::getList())
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('invest_date', DateFormatter::format())
            ->with('user_id', auth()->user()->id);
    }

    public function store(InvestStoreRequest $request)
    {
        $client = Client::firstOrCreateByUniqueName($request->input('client_name'));

        $fromInvest = Invest::find($request->input('invest_id'));

        $toInvest = Invest::create([
            'client_id' => $client->id,
            'company_profile_id' => $request->input('company_profile_id'),
            'invest_date' => DateFormatter::unformat($request->input('invest_date')),
            'group_id' => $request->input('group_id'),
            'currency_code' => $fromInvest->currency_code,
            'exchange_rate' => $fromInvest->exchange_rate,
            'terms' => $fromInvest->terms,
            'footer' => $fromInvest->footer,
            'template' => $fromInvest->template,
            'summary' => $fromInvest->summary,
            'discount' => $fromInvest->discount,
        ]);

        foreach ($fromInvest->items as $item) {
            InvestItem::create([
                'invest_id' => $toInvest->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax_rate_id' => $item->tax_rate_id,
                'tax_rate_2_id' => $item->tax_rate_2_id,
                'display_order' => $item->display_order,
            ]);
        }

        // Copy the custom fields
        $custom = collect($fromInvest->custom)->except('invest_id')->toArray();
        $toInvest->custom->update($custom);

        return response()->json(['id' => $toInvest->id], 200);
    }
}