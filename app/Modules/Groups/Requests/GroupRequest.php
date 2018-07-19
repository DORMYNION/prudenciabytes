<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Groups\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => trans('fi.name'),
            'next_id' => trans('fi.next_number'),
            'left_pad' => trans('fi.left_pad'),
            'format' => trans('fi.format'),
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'next_id' => 'required|integer',
            'left_pad' => 'required|numeric',
            'format' => 'required',
        ];
    }
}