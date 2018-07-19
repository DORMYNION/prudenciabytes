<?php

namespace FI\Events;

use FI\Modules\Loans\Models\Loan;
use FI\Modules\RecurringLoans\Models\RecurringLoan;
use Illuminate\Queue\SerializesModels;

class LoanCreatedRecurring extends Event
{
    use SerializesModels;

    public function __construct(Loan $loan, RecurringLoan $recurringLoan)
    {
        $this->loan = $loan;
        $this->recurringLoan = $recurringLoan;
    }
}
