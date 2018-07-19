<?php

namespace FI\Modules\Loans\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'company_profile_id' => trans('fi.company_profile'),
            'client_name' => trans('fi.client'),
            'client_id' => trans('fi.client'),
            'user_id' => trans('fi.user'),
            'loan_date' => trans('fi.date'),
            'due_at' => trans('fi.due'),
            'number' => trans('fi.loan_number'),
            'loan_status_id' => trans('fi.status'),
            'exchange_rate' => trans('fi.exchange_rate'),
            'template' => trans('fi.template'),
            'group_id' => trans('fi.group'),
            'items.*.price' => trans('fi.price'),
            'items.*.tenor' => trans('fi.tenor'),
            'items.*.interest' => trans('fi.interest'),
        ];
    }

    public function rules()
    {
        return [
            'company_profile_id' => 'required|integer|exists:company_profiles,id',
            'client_name' => 'required',
            'loan_date' => 'required',
            'user_id' => 'required',
        ];
    }
}
