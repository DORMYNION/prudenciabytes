<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\RecurringLoans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\RecurringLoans\Models\RecurringLoanItem;

class RecurringLoanItemController extends Controller
{
    public function delete()
    {
        RecurringLoanItem::destroy(request('id'));
    }
}