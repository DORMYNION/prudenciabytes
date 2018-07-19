<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'email' => trans('fi.email'),
            'password' => trans('fi.password'),
            'name' => trans('fi.name'),
        ];
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'name' => 'required',
        ];
    }
}