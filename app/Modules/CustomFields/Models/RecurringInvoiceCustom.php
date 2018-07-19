<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringLoanCustom extends Model
{
    /**
     * The table name
     * @var string
     */
    protected $table = 'recurring_loans_custom';

    /**
     * The primary key
     * @var string
     */
    protected $primaryKey = 'recurring_loan_id';

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = [];
}