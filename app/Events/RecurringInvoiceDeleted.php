<?php

namespace FI\Events;

use FI\Modules\RecurringLoans\Models\RecurringLoan;
use Illuminate\Queue\SerializesModels;

class RecurringLoanDeleted extends Event
{
    use SerializesModels;

    public $recurringLoan;

    public function __construct(RecurringLoan $recurringLoan)
    {
        $this->recurringLoan = $recurringLoan;
    }
}
