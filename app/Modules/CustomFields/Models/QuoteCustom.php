<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class InvestCustom extends Model
{
    /**
     * The table name
     * @var string
     */
    protected $table = 'invests_custom';

    /**
     * The primary key
     * @var string
     */
    protected $primaryKey = 'invest_id';

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = [];
}