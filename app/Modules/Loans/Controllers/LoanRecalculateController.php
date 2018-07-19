<?php

namespace FI\Modules\Loans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Support\LoanCalculate;

class LoanRecalculateController extends Controller
{
    private $loanCalculate;

    public function __construct(LoanCalculate $loanCalculate)
    {
        $this->loanCalculate = $loanCalculate;
    }

    public function recalculate()
    {
        try {
            $this->loanCalculate->calculateAll();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true, 'message' => trans('fi.recalculation_complete')], 200);
    }
}
