<?php

namespace FI\Events\Listeners;

use FI\Events\LoanEmailed;
use FI\Support\Statuses\LoanStatuses;

class LoanEmailedListener
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
     * @param  LoanEmailed $event
     * @return void
     */
    public function handle(LoanEmailed $event)
    {
        // Change the status to sent if the status is currently draft
        if ($event->loan->loan_status_id == LoanStatuses::getStatusId('draft')) {
            $event->loan->loan_status_id = LoanStatuses::getStatusId('sent');
            $event->loan->save();
        }
    }
}
