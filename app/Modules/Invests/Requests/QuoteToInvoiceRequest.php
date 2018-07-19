<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Requests;

class InvestToLoanRequest extends InvestStoreRequest
{
    public function rules()
    {
        return [
            'client_id' => 'required',
            'loan_date' => 'required',
            'group_id' => 'required',
        ];
    }
}