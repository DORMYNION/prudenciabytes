<?php

namespace FI\Modules\Loans\Support;

use FI\Support\Calculators\Calculator;
use FI\Support\Calculators\Interfaces\PayableInterface;

class LoanCalculator extends Calculator implements PayableInterface
{
    /**
     * Call the calculation methods.
     */
    public function calculate()
    {
        $this->calculateItems();
        $this->calculatePayments();
    }

    /**
     * Calculate additional properties.
     *
     * @return void
     */
    public function calculatePayments()
    {
        if (!$this->isCanceled) {
            $this->calculatedAmount['balance'] = round($this->calculatedAmount['total'], 2) - $this->calculatedAmount['paid'];
        } else {
            $this->calculatedAmount['balance'] = 0;
        }
    }

    /**
     * Set the total paid amount.
     *
     * @param float $totalPaid
     */
    public function setTotalPaid($totalPaid)
    {
        if ($totalPaid) {
            $this->calculatedAmount['paid'] = $totalPaid;
        } else {
            $this->calculatedAmount['paid'] = 0;
        }
    }
}
