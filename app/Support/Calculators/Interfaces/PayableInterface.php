<?php

namespace FI\Support\Calculators\Interfaces;

interface PayableInterface
{
    /**
     * Set the total paid amount.
     *
     * @param float $totalPaid
     */
    public function setTotalPaid($totalPaid);

    /**
     * Calculate additional properties.
     *
     * @return void
     */
    public function calculatePayments();
}
