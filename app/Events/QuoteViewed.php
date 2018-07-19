<?php

namespace FI\Events;

use FI\Modules\Invests\Models\Invest;
use Illuminate\Queue\SerializesModels;

class InvestViewed extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invest $invest)
    {
        $this->invest = $invest;
    }
}
