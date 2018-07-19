<?php

namespace FI\Events\Listeners;

use FI\Events\LoanCreated;
use FI\Modules\CustomFields\Models\LoanCustom;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Loans\Support\LoanCalculate;

class LoanCreatedListener
{
    private $loanCalculate;

    public function __construct(LoanCalculate $loanCalculate)
    {
        $this->loanCalculate = $loanCalculate;
    }

    public function handle(LoanCreated $event)
    {
        // Create the empty loan amount record.
        $this->loanCalculate->calculate($event->loan);

        // Increment the next id.
        Group::incrementNextId($event->loan);

        // Create the custom loan record.
        $event->loan->custom()->save(new LoanCustom());
    }
}
