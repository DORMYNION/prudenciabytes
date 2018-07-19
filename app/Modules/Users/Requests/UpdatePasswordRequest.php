<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Users\Requests;

class UpdatePasswordRequest extends UserStoreRequest
{
    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
        ];
    }
}