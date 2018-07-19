<?php

namespace FI\Events\Listeners;

use FI\Events\InvestViewed;

class InvestViewedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvestViewed $event
     * @return void
     */
    public function handle(InvestViewed $event)
    {
        if (request('disableFlag') != 1) {
            if (auth()->guest() or auth()->user()->user_type == 'client') {
                $event->invest->activities()->create(['activity' => 'public.viewed']);
                $event->invest->viewed = 1;
                $event->invest->save();
            }
        }
    }
}
