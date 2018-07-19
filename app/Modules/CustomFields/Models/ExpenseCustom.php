<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCustom extends Model
{
    protected $table = 'expenses_custom';

    protected $primaryKey = 'expense_id';

    protected $guarded = [];
}