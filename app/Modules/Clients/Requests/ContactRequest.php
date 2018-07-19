<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => trans('fi.name'),
            'email' => trans('fi.email'),
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
        ];
    }
}