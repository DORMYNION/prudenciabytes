<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\RecurringLoans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Loans\Support\LoanTemplates;
use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Modules\RecurringLoans\Models\RecurringLoan;
use FI\Modules\RecurringLoans\Models\RecurringLoanItem;
use FI\Modules\RecurringLoans\Requests\RecurringLoanUpdateRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\DateFormatter;
use FI\Support\Frequency;
use FI\Traits\ReturnUrl;

class RecurringLoanEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
        $recurringLoan = RecurringLoan::with(['items.amount.item.recurringLoan.currency'])->find($id);

        return view('recurring_loans.edit')
            ->with('recurringLoan', $recurringLoan)
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('recurring_loans')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', LoanTemplates::lists())
            ->with('itemCount', count($recurringLoan->recurringLoanItems))
            ->with('frequencies', Frequency::lists())
            ->with('groups', Group::getList());
    }

    public function update(RecurringLoanUpdateRequest $request, $id)
    {
        $input = $request->except(['items', 'custom', 'apply_exchange_rate']);
        $input['next_date'] = DateFormatter::unformat($input['next_date']);
        $input['stop_date'] = DateFormatter::unformat($input['stop_date']);

        // Save the recurring loan.
        $recurringLoan = RecurringLoan::find($id);
        $recurringLoan->fill($input);
        $recurringLoan->save();

        // Save the custom fields.
        $recurringLoan->custom->update($request->input('custom', []));

        // Save the items.
        foreach ($request->input('items') as $item) {
            $item['apply_exchange_rate'] = request('apply_exchange_rate');

            if (!isset($item['id']) or (!$item['id'])) {
                $saveItemAsLookup = $item['save_item_as_lookup'];
                unset($item['save_item_as_lookup']);

                RecurringLoanItem::create($item);

                if ($saveItemAsLookup) {
                    ItemLookup::create([
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'tax_rate_id' => $item['tax_rate_id'],
                        'tax_rate_2_id' => $item['tax_rate_2_id'],
                    ]);
                }
            } else {
                $recurringLoanItem = RecurringLoanItem::find($item['id']);
                $recurringLoanItem->fill($item);
                $recurringLoanItem->save();
            }
        }
    }

    public function refreshEdit($id)
    {
        $recurringLoan = RecurringLoan::with(['items.amount.item.recurringLoan.currency'])->find($id);

        return view('recurring_loans._edit')
            ->with('recurringLoan', $recurringLoan)
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('recurring_loans')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', LoanTemplates::lists())
            ->with('itemCount', count($recurringLoan->recurringLoanItems))
            ->with('frequencies', Frequency::lists())
            ->with('groups', Group::getList());
    }

    public function refreshTotals()
    {
        return view('recurring_loans._edit_totals')
            ->with('recurringLoan', RecurringLoan::with(['items.amount.item.recurringLoan.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('recurring_loans._edit_to')
            ->with('recurringLoan', RecurringLoan::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('recurring_loans._edit_from')
            ->with('recurringLoan', RecurringLoan::find(request('id')));
    }

    public function updateClient()
    {
        RecurringLoan::where('id', request('id'))->update(['client_id' => request('client_id')]);
    }

    public function updateCompanyProfile()
    {
        RecurringLoan::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}