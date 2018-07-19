<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Support;

class CustomFields
{
    /**
     * Provide an array of available custom table names.
     *
     * @return array
     */
    public static function tableNames()
    {
        return [
            'clients' => trans('fi.clients'),
            'company_profiles' => trans('fi.company_profiles'),
            'expenses' => trans('fi.expenses'),
            'loans' => trans('fi.loans'),
            'invests' => trans('fi.invests'),
            'recurring_loans' => trans('fi.recurring_loans'),
            'payments' => trans('fi.payments'),
            'users' => trans('fi.users'),
        ];
    }

    /**
     * Provide an array of available custom field types.
     *
     * @return array
     */
    public static function fieldTypes()
    {
        return [
            'text' => trans('fi.text'),
            'dropdown' => trans('fi.dropdown'),
            'textarea' => trans('fi.textarea'),
        ];
    }
}