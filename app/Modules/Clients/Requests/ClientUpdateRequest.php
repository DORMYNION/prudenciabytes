<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Clients\Requests;

class ClientUpdateRequest extends ClientStoreRequest
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['unique_name'] = 'required|unique:clients,unique_name,' . $this->route('id');

        return $rules;
    }
}