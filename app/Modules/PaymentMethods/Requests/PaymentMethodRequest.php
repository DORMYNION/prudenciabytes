<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\PaymentMethods\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => trans('fi.payment_method'),
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}