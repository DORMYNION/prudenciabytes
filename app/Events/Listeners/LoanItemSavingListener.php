<?php

namespace FI\Events\Listeners;

use FI\Events\LoanItemSaving;
use FI\Modules\Loans\Models\LoanItem;

class LoanItemSavingListener
{
    public function handle(LoanItemSaving $event)
    {
        $item = $event->loanItem;

        $applyExchangeRate = $item->apply_exchange_rate;
        unset($item->apply_exchange_rate);

        if ($applyExchangeRate == true) {
            $item->price = $item->price * $item->loan->exchange_rate;
        }

        if (!$item->display_order) {
            $displayOrder = LoanItem::where('loan_id', $item->loan_id)->max('display_order');

            $displayOrder++;

            $item->display_order = $displayOrder;
        }

        if (is_null($item->tenor)) {
            $item->tenor = 0;
        }

        if (is_null($item->interest)) {
            $item->interest = 0;
        }
    }
}
