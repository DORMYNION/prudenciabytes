<?php

namespace FI\Events;

use FI\Modules\Loans\Models\LoanItem;
use Illuminate\Queue\SerializesModels;

class LoanItemSaving extends Event
{
    use SerializesModels;

    public function __construct(LoanItem $loanItem)
    {
        $this->loanItem = $loanItem;
    }
}
