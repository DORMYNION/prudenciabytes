<?php

namespace FI\Events\Listeners;

use FI\Events\RecurringLoanModified;
use FI\Modules\RecurringLoans\Support\RecurringLoanCalculate;

class RecurringLoanModifiedListener
{
    private $recurringLoanCalculate;

    public function __construct(RecurringLoanCalculate $recurringLoanCalculate)
    {
        $this->recurringLoanCalculate = $recurringLoanCalculate;
    }

    public function handle(RecurringLoanModified $event)
    {
        $this->recurringLoanCalculate->calculate($event->recurringLoan->id);
    }
}
