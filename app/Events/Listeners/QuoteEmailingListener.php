<?php

namespace FI\Events\Listeners;

use FI\Events\InvestEmailing;
use FI\Support\DateFormatter;

class InvestEmailingListener
{
    public function handle(InvestEmailing $event)
    {
        if (config('fi.resetInvestDateEmailDraft') and $event->invest->status_text == 'draft') {
            $event->invest->invest_date = date('Y-m-d');
            $event->invest->expires_at = DateFormatter::incrementDateByDays(date('Y-m-d'), config('fi.investsExpireAfter'));
            $event->invest->save();
        }
    }
}
