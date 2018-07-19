<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Settings\Requests;

use FI\Modules\Settings\Rules\ValidFile;
use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'setting.loansDueAfter' => trans('fi.loans_due_after'),
            'setting.investsExpireAfter' => trans('fi.invests_expire_after'),
            'setting.pdfBinaryPath' => trans('fi.binary_path'),
        ];
    }

    public function rules()
    {
        $rules = [
            'setting.loansDueAfter' => 'required|numeric',
            'setting.investsExpireAfter' => 'required|numeric',
            'setting.pdfBinaryPath' => ['required_if:setting.pdfDriver,wkhtmltopdf', new ValidFile],
        ];

        foreach (config('fi.settingValidationRules') as $settingValidationRules) {
            $rules = array_merge($rules, $settingValidationRules['rules']);
        }

        return $rules;
    }
}