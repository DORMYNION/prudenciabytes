<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\PaymentMethods\Models;

use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use Sortable;

    protected $guarded = ['id'];

    protected $sortable = ['name'];

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function getList()
    {
        return self::orderBy('name')->pluck('name', 'id')->all();
    }
}