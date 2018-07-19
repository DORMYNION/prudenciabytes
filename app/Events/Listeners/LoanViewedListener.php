<?php

namespace FI\Events\Listeners;

use FI\Events\LoanViewed;

class LoanViewedListener
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
     * @param  LoanViewed $event
     * @return void
     */
    public function handle(LoanViewed $event)
    {
        if (request('disableFlag') != 1) {
            if (auth()->guest() or auth()->user()->user_type == 'client') {
                $event->loan->activities()->create(['activity' => 'public.viewed']);
                $event->loan->viewed = 1;
                $event->loan->save();
            }
        }
    }
}
