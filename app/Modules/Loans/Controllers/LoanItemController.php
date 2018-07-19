<?php

namespace FI\Modules\Loans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Models\LoanItem;

class LoanItemController extends Controller
{
    public function delete()
    {
        LoanItem::destroy(request('id'));
    }
}
