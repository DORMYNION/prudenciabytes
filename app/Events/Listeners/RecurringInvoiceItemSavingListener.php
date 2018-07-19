<?php

namespace FI\Events\Listeners;

use FI\Events\RecurringLoanItemSaving;
use FI\Modules\RecurringLoans\Models\RecurringLoanItem;

class RecurringLoanItemSavingListener
{
    public function handle(RecurringLoanItemSaving $event)
    {
        $item = $event->recurringLoanItem;

        $applyExchangeRate = $item->apply_exchange_rate;
        unset($item->apply_exchange_rate);

        if ($applyExchangeRate == true) {
            $item->price = $item->price * $item->loan->exchange_rate;
        }

        if (!$item->display_order) {
            $displayOrder = RecurringLoanItem::where('loan_id', $item->recurring_loan_id)->max('display_order');

            $displayOrder++;

            $item->display_order = $displayOrder;
        }
    }
}
