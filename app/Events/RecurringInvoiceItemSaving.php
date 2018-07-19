<?php

namespace FI\Events;

use FI\Modules\RecurringLoans\Models\RecurringLoanItem;
use Illuminate\Queue\SerializesModels;

class RecurringLoanItemSaving extends Event
{
    use SerializesModels;

    public function __construct(RecurringLoanItem $recurringLoanItem)
    {
        $this->recurringLoanItem = $recurringLoanItem;
    }
}
