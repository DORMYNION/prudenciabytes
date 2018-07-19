<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Setup\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'user.name' => trans('fi.name'),
            'user.email' => trans('fi.email'),
            'user.password' => trans('fi.password'),
            'company_profile.company' => trans('fi.company_profile'),
        ];
    }

    public function rules()
    {
        return [
            'user.name' => 'required',
            'user.email' => 'required|email',
            'user.password' => 'required|confirmed',
            'company_profile.company' => 'required',
        ];
    }
}