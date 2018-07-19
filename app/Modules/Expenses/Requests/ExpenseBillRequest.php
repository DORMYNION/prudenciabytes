<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Expenses\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseBillRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'loan_id' => trans('fi.loan'),
            'item_name' => trans('fi.item'),
        ];
    }

    public function rules()
    {
        return [
            'loan_id' => 'required',
            'item_name' => 'required',
        ];
    }
}