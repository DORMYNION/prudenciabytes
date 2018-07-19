<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCustom extends Model
{
    /**
     * The table name
     * @var string
     */
    protected $table = 'loans_custom';

    /**
     * The primary key
     * @var string
     */
    protected $primaryKey = 'loan_id';

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = [];
}