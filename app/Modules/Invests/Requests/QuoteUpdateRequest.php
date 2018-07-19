<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Requests;

use FI\Support\NumberFormatter;

class InvestUpdateRequest extends InvestStoreRequest
{
    public function prepareForValidation()
    {
        $request = $this->all();

        if (isset($request['items'])) {
            foreach ($request['items'] as $key => $item) {
                $request['items'][$key]['quantity'] = NumberFormatter::unformat($item['quantity']);
                $request['items'][$key]['price'] = NumberFormatter::unformat($item['price']);
            }
        }

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'summary' => 'max:255',
            'invest_date' => 'required',
            'number' => 'required',
            'invest_status_id' => 'required',
            'exchange_rate' => 'required|numeric',
            'template' => 'required',
            'items.*.name' => 'required_with:items.*.price,items.*.quantity',
            'items.*.quantity' => 'required_with:items.*.price,items.*.name|numeric',
            'items.*.price' => 'required_with:items.*.name,items.*.quantity|numeric',
        ];
    }
}