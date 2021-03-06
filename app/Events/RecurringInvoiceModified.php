<?php

namespace FI\Events;

use FI\Modules\RecurringLoans\Models\RecurringLoan;
use Illuminate\Queue\SerializesModels;

class RecurringLoanModified extends Event
{
    use SerializesModels;

    public $recurringLoan;

    public function __construct(RecurringLoan $recurringLoan)
    {
        $this->recurringLoan = $recurringLoan;
    }
}
