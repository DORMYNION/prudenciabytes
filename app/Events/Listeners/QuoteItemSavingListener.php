<?php

namespace FI\Events\Listeners;

use FI\Events\InvestItemSaving;
use FI\Modules\Invests\Models\InvestItem;

class InvestItemSavingListener
{
    public function handle(InvestItemSaving $event)
    {
        $item = $event->investItem;

        $applyExchangeRate = $item->apply_exchange_rate;
        unset($item->apply_exchange_rate);

        if ($applyExchangeRate == true) {
            $item->price = $item->price * $item->invest->exchange_rate;
        }

        if (!$item->display_order) {
            $displayOrder = InvestItem::where('invest_id', $item->invest_id)->max('display_order');

            $displayOrder++;

            $item->display_order = $displayOrder;
        }
    }
}
