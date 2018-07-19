<?php


namespace FI\Modules\Loans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Models\LoanItem;
use FI\Modules\Loans\Requests\LoanUpdateRequest;
use FI\Modules\Loans\Support\LoanTemplates;
use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\DateFormatter;
use FI\Support\Statuses\LoanStatuses;
use FI\Traits\ReturnUrl;

class LoanEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
        $loan = Loan::with(['items.amount.item.loan.currency'])->find($id);

        return view('loans.edit')
            ->with('loan', $loan)
            ->with('statuses', LoanStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('loans')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', LoanTemplates::lists());
            ->with('itemCount', count($loan->loanItems));
    }


    public function update(LoanUpdateRequest $request, $id)
    {
        // Unformat the loan dates.
        $loanInput = $request->except(['items', 'custom', 'apply_exchange_rate']);
        $loanInput['loan_date'] = DateFormatter::unformat($loanInput['loan_date']);
        $loanInput['due_at'] = DateFormatter::unformat($loanInput['due_at']);

        // Save the loan.
        $loan = Loan::find($id);
        $loan->fill($loanInput);
        $loan->save();

        // Save the custom fields.
        $loan->custom->update(request('custom', []));

        // Save the items.
        foreach ($request->input('items') as $item) {
            $item['apply_exchange_rate'] = request('apply_exchange_rate');

            if (!isset($item['id']) or (!$item['id'])) {
                $saveItemAsLookup = $item['save_item_as_lookup'];
                unset($item['save_item_as_lookup']);

                LoanItem::create($item);

                if ($saveItemAsLookup) {
                    ItemLookup::create([
                        'price' => $item['price'],
                        'tenor' => $item['tenor'],
                        'interest' => $item['interest'],
                    ]);
                }
            } else {
                $loanItem = LoanItem::find($item['id']);
                $loanItem->fill($item);
                $loanItem->save();
            }
        }
    }

    public function refreshEdit($id)
    {
        $loan = Loan::with(['items.amount.item.loan.currency'])->find($id);

        return view('loans._edit')
            ->with('loan', $loan)
            ->with('statuses', LoanStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('loans')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', LoanTemplates::lists())
            ->with('itemCount', count($loan->loanItems));
    }

    public function refreshTotals()
    {
        return view('loans._edit_totals')
            ->with('loan', Loan::with(['items.amount.item.loan.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('loans._edit_to')
            ->with('loan', Loan::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('loans._edit_from')
            ->with('loan', Loan::find(request('id')));
    }

    public function updateClient()
    {
        Loan::where('id', request('id'))->update(['client_id' => request('client_id')]);
    }

    public function updateCompanyProfile()
    {
        Loan::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}
