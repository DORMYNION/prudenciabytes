<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Invests\Support\InvestCalculate;

class InvestRecalculateController extends Controller
{
    private $investCalculate;

    public function __construct(InvestCalculate $investCalculate)
    {
        $this->investCalculate = $investCalculate;
    }

    public function recalculate()
    {
        try {
            $this->investCalculate->calculateAll();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => trans('fi.recalculation_complete'),
        ], 200);
    }
}