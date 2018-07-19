<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Requests;

class CustomFieldUpdateRequest extends CustomFieldStoreRequest
{
    public function rules()
    {
        return [
            'field_label' => 'required',
            'field_type' => 'required',
        ];
    }
}