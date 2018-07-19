<?php

namespace FI\Modules\Loans\Requests;

use FI\Support\NumberFormatter;

class LoanUpdateRequest extends LoanStoreRequest
{
    public function prepareForValidation()
    {
        $request = $this->all();

        if (isset($request['items'])) {
            foreach ($request['items'] as $key => $item) {
                $request['items'][$key]['price'] = NumberFormatter::unformat($item['price']);
                $request['items'][$key]['tenor'] = NumberFormatter::unformat($item['tenor']);
                $request['items'][$key]['interest'] = NumberFormatter::unformat($item['interest']);
            }
        }

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'loan_date' => 'required',
            'due_at' => 'required',
            'number' => 'required',
            'loan_status_id' => 'required',
            'exchange_rate' => 'required|numeric',
            'template' => 'required',
            'items.*.price' => 'required_with:items.*.tenor,items.*.interest|numeric',
            'items.*.tenor' => 'required_with:items.*.price,items.*.interest|numeric',
            'items.*.interest' => 'required_with:items.*.price,items.*.tenor|numeric',
        ];
    }
}
