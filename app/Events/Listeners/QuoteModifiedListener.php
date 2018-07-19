<?php

namespace FI\Events\Listeners;

use FI\Events\InvestModified;
use FI\Modules\Invests\Support\InvestCalculate;

class InvestModifiedListener
{
    public function __construct(InvestCalculate $investCalculate)
    {
        $this->investCalculate = $investCalculate;
    }

    public function handle(InvestModified $event)
    {
        // Calculate the invest and item amounts
        $this->investCalculate->calculate($event->invest);
    }
}
