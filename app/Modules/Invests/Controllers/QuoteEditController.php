<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Modules\Invests\Models\Invest;
use FI\Modules\Invests\Models\InvestItem;
use FI\Modules\Invests\Requests\InvestUpdateRequest;
use FI\Modules\Invests\Support\InvestTemplates;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\DateFormatter;
use FI\Support\Statuses\InvestStatuses;
use FI\Traits\ReturnUrl;

class InvestEditController extends Controller
{
    use ReturnUrl;

    public function edit($id)
    {
        $invest = Invest::with(['items.amount.item.invest.currency'])->find($id);

        return view('invests.edit')
            ->with('invest', $invest)
            ->with('statuses', InvestStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('invests')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', InvestTemplates::lists())
            ->with('itemCount', count($invest->investItems));
    }

    public function update(InvestUpdateRequest $request, $id)
    {
        // Unformat the invest dates.
        $input = $request->except(['items', 'custom', 'apply_exchange_rate']);
        $input['invest_date'] = DateFormatter::unformat($input['invest_date']);
        $input['expires_at'] = DateFormatter::unformat($input['expires_at']);

        // Save the invest.
        $invest = Invest::find($id);
        $invest->fill($input);
        $invest->save();

        // Save the custom fields.
        $invest->custom->update($request->input('custom', []));

        // Save the items.
        foreach ($request->input('items') as $item) {
            $item['apply_exchange_rate'] = $request->input('apply_exchange_rate');

            if (!isset($item['id']) or (!$item['id'])) {
                $saveItemAsLookup = $item['save_item_as_lookup'];
                unset($item['save_item_as_lookup']);

                InvestItem::create($item);

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
                $investItem = InvestItem::find($item['id']);
                $investItem->fill($item);
                $investItem->save();
            }
        }

        return response()->json(['success' => true], 200);
    }

    public function refreshEdit($id)
    {
        $invest = Invest::with(['items.amount.item.invest.currency'])->find($id);

        return view('invests._edit')
            ->with('invest', $invest)
            ->with('statuses', InvestStatuses::lists())
            ->with('currencies', Currency::getList())
            ->with('taxRates', TaxRate::getList())
            ->with('customFields', CustomField::forTable('invests')->get())
            ->with('returnUrl', $this->getReturnUrl())
            ->with('templates', InvestTemplates::lists())
            ->with('itemCount', count($invest->investItems));
    }

    public function refreshTotals()
    {
        return view('invests._edit_totals')
            ->with('invest', Invest::with(['items.amount.item.invest.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('invests._edit_to')
            ->with('invest', Invest::find(request('id')));
    }

    public function refreshFrom()
    {
        return view('invests._edit_from')
            ->with('invest', Invest::find(request('id')));
    }

    public function updateClient()
    {
        Invest::where('id', request('id'))->update(['client_id' => request('client_id')]);
    }

    public function updateCompanyProfile()
    {
        Invest::where('id', request('id'))->update(['company_profile_id' => request('company_profile_id')]);
    }
}