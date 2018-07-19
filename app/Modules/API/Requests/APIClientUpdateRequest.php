<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\API\Requests;

use FI\Modules\Clients\Requests\ClientUpdateRequest;

class APIClientUpdateRequest extends ClientUpdateRequest
{
    public function rules()
    {
        return [
            'id' => 'required',
            'email' => 'email',
            'unique_name' => 'sometimes|unique:clients,unique_name,' . $this->input('id'),
        ];
    }
}