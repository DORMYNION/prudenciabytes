<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Currencies\Requests;

class CurrencyUpdateRequest extends CurrencyStoreRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:currencies,code,' . $this->route('id'),
            'symbol' => 'required',
            'placement' => 'required',
        ];
    }
}