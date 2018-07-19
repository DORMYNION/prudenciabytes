<?php

namespace FI\Events\Listeners;

use FI\Events\RecurringLoanDeleted;

class RecurringLoanDeletedListener
{
    public function __construct()
    {
        //
    }

    public function handle(RecurringLoanDeleted $event)
    {
        foreach ($event->recurringLoan->items as $item) {
            $item->delete();
        }

        $event->recurringLoan->amount()->delete();
        $event->recurringLoan->custom()->delete();
    }
}
