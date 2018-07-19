<?php

namespace FI\Events\Listeners;

use FI\Events\InvestEmailed;
use FI\Support\Statuses\InvestStatuses;

class InvestEmailedListener
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
     * @param  InvestEmailed $event
     * @return void
     */
    public function handle(InvestEmailed $event)
    {
        // Change the status to sent if the status is currently draft
        if ($event->invest->invest_status_id == InvestStatuses::getStatusId('draft')) {
            $event->invest->invest_status_id = InvestStatuses::getStatusId('sent');
            $event->invest->save();
        }
    }
}
