<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YearRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'year' => trans('fi.year'),
        ];
    }

    public function rules()
    {
        return [
            'year' => 'required',
        ];
    }
}