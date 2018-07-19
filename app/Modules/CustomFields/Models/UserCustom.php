<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class UserCustom extends Model
{
    /**
     * The table name
     * @var string
     */
    protected $table = 'users_custom';

    /**
     * The primary key
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = [];
}