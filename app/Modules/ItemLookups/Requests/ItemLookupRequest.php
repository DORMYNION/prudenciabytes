<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\ItemLookups\Requests;

use FI\Support\NumberFormatter;
use Illuminate\Foundation\Http\FormRequest;

class ItemLookupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => trans('fi.name'),
            'price' => trans('fi.price'),
        ];
    }

    public function prepareForValidation()
    {
        $request = $this->all();

        $request['price'] = NumberFormatter::unformat($request['price']);

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
        ];
    }
}