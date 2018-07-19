<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Import\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'import_type' => 'required',
            'import_file' => 'required|mimes:txt',
        ];
    }
}