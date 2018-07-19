<?php

namespace FI\Events\Listeners;

use FI\Events\LoanEmailing;
use FI\Support\DateFormatter;

class LoanEmailingListener
{
    public function handle(LoanEmailing $event)
    {
        if (config('fi.resetLoanDateEmailDraft') and $event->loan->status_text == 'draft') {
            $event->loan->loan_date = date('Y-m-d');
            $event->loan->due_at = DateFormatter::incrementDateByDays(date('Y-m-d'), config('fi.loansDueAfter'));
            $event->loan->save();
        }
    }
}
