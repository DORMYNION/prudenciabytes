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
use FI\Modules\RecurringLoans\Models\RecurringLoanItem;
use FI\Modules\RecurringLoans\Requests\RecurringLoanStoreRequest;
use FI\Support\DateFormatter;
use FI\Support\Frequency;

class RecurringLoanCopyController extends Controller
{
    public function create()
    {
        return view('recurring_loans._modal_copy')
            ->with('recurringLoan', RecurringLoan::find(request('recurring_loan_id')))
            ->with('groups', Group::getList())
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('frequencies', Frequency::lists());
    }

    public function store(RecurringLoanStoreRequest $request)
    {
        $client = Client::firstOrCreateByUniqueName($request->input('client_name'));

        $fromRecurringLoan = RecurringLoan::find($request->input('recurring_loan_id'));

        $toRecurringLoan = RecurringLoan::create([
            'client_id' => $client->id,
            'company_profile_id' => $request->input('company_profile_id'),
            'group_id' => $request->input('group_id'),
            'currency_code' => $fromRecurringLoan->currency_code,
            'exchange_rate' => $fromRecurringLoan->exchange_rate,
            'terms' => $fromRecurringLoan->terms,
            'footer' => $fromRecurringLoan->footer,
            'template' => $fromRecurringLoan->template,
            'summary' => $fromRecurringLoan->summary,
            'discount' => $fromRecurringLoan->discount,
            'recurring_frequency' => $request->input('recurring_frequency'),
            'recurring_period' => $request->input('recurring_period'),
            'next_date' => DateFormatter::unformat($request->input('next_date')),
            'stop_date' => ($request->input('stop_date') ? DateFormatter::unformat($request->input('stop_date')) : '0000-00-00'),
        ]);

        foreach ($fromRecurringLoan->items as $item) {
            RecurringLoanItem::create([
                'recurring_loan_id' => $toRecurringLoan->id,
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
        $custom = collect($fromRecurringLoan->custom)->except('recurring_loan_id')->toArray();
        $toRecurringLoan->custom->update($custom);

        return response()->json(['success' => true, 'url' => route('recurringLoans.edit', [$toRecurringLoan->id])], 200);
    }
}