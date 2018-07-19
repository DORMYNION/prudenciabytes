<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Invests\Models\InvestItem;

class InvestItemController extends Controller
{
    public function delete()
    {
        InvestItem::destroy(request('id'));
    }
}