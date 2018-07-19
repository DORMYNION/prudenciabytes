<?php

namespace FI\Events\Listeners;

use FI\Events\InvestCreated;
use FI\Modules\CustomFields\Models\InvestCustom;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Invests\Support\InvestCalculate;

class InvestCreatedListener
{
    public function __construct(InvestCalculate $investCalculate)
    {
        $this->investCalculate = $investCalculate;
    }

    public function handle(InvestCreated $event)
    {
        // Create the empty invest amount record
        $this->investCalculate->calculate($event->invest);

        // Increment the next id
        Group::incrementNextId($event->invest);

        // Create the custom invest record.
        $event->invest->custom()->save(new InvestCustom());
    }
}
