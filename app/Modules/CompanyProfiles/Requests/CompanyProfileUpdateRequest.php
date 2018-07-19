<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CompanyProfiles\Requests;

class CompanyProfileUpdateRequest extends CompanyProfileStoreRequest
{
    public function rules()
    {
        return ['company' => 'required|unique:company_profiles,company,' . $this->route('id')];
    }
}