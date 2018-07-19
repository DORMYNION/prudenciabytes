<?php

namespace FI\Composers;

use FI\Support\Statuses\LoanStatuses;

class LoanTableComposer
{
    public function compose($view)
    {
        $view->with('statuses', LoanStatuses::statuses());
    }
}
