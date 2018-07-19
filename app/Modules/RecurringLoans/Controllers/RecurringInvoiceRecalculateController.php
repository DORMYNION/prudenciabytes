<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\RecurringLoans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\RecurringLoans\Support\RecurringLoanCalculate;

class RecurringLoanRecalculateController extends Controller
{
    private $recurringLoanCalculate;

    public function __construct(RecurringLoanCalculate $recurringLoanCalculate)
    {
        $this->recurringLoanCalculate = $recurringLoanCalculate;
    }

    public function recalculate()
    {
        try {
            $this->recurringLoanCalculate->calculateAll();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true, 'message' => trans('fi.recalculation_complete')], 200);
    }
}