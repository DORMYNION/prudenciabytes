<?php

namespace FI\Events;

use FI\Modules\Invests\Models\Invest;
use Illuminate\Queue\SerializesModels;

class InvestHTMLCreating extends Event
{
    use SerializesModels;

    public function __construct(Invest $invest)
    {
        $this->invest = $invest;
    }
}
