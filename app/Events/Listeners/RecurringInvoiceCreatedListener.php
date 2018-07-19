<?php

namespace FI\Events\Listeners;

use FI\Events\RecurringLoanCreated;
use FI\Modules\CustomFields\Models\RecurringLoanCustom;
use FI\Modules\RecurringLoans\Support\RecurringLoanCalculate;

class RecurringLoanCreatedListener
{
    private $recurringLoanCalculate;

    public function __construct(RecurringLoanCalculate $recurringLoanCalculate)
    {
        $this->recurringLoanCalculate = $recurringLoanCalculate;
    }

    public function handle(RecurringLoanCreated $event)
    {
        // Create the empty loan amount record.
        $this->recurringLoanCalculate->calculate($event->recurringLoan->id);

        // Create the custom record.
        $event->recurringLoan->custom()->save(new RecurringLoanCustom());
    }
}
