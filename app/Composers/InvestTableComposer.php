<?php

namespace FI\Composers;

use FI\Support\Statuses\InvestStatuses;

class InvestTableComposer
{
    public function compose($view)
    {
        $view->with('statuses', InvestStatuses::statuses());
    }
}
