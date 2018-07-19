<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\API\Requests;

use FI\Modules\Invests\Requests\InvestStoreRequest;

class APIInvestStoreRequest extends InvestStoreRequest
{
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['user_id']);

        return $rules;
    }
}