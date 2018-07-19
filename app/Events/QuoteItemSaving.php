<?php

namespace FI\Events;

use FI\Modules\Invests\Models\InvestItem;
use Illuminate\Queue\SerializesModels;

class InvestItemSaving extends Event
{
    use SerializesModels;

    public function __construct(InvestItem $investItem)
    {
        $this->investItem = $investItem;
    }
}
