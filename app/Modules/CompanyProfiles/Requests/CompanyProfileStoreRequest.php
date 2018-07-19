<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CompanyProfiles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return ['company' => trans('fi.company')];
    }

    public function rules()
    {
        return ['company' => 'required|unique:company_profiles,company'];
    }
}