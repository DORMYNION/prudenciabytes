<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Users\Requests;

class UserUpdateRequest extends UserStoreRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->route('id'),
            'name' => 'required',
        ];
    }
}