<?php


namespace FI\Modules\API\Requests;

use FI\Modules\Loans\Requests\LoanStoreRequest;

class APILoanStoreRequest extends LoanStoreRequest
{
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['user_id']);

        return $rules;
    }
}
