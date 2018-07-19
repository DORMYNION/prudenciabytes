<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Expenses\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseVendor extends Model
{
    protected $table = 'expense_vendors';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function getList()
    {
        return self::whereIn('id', function ($query) {
            $query->select('vendor_id')->distinct()->from('expenses');
        })->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
}