<?php

namespace FI\Events\Listeners;

use FI\Events\LoanModified;
use FI\Modules\Loans\Support\LoanCalculate;

class LoanModifiedListener
{
    public function __construct(LoanCalculate $loanCalculate)
    {
        $this->loanCalculate = $loanCalculate;
    }

    public function handle(LoanModified $event)
    {
        $this->loanCalculate->calculate($event->loan);
    }
}
