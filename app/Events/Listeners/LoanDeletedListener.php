<?php

namespace FI\Events\Listeners;

use FI\Events\LoanDeleted;
use FI\Modules\Expenses\Models\Expense;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Invests\Models\Invest;

class LoanDeletedListener
{
    public function __construct()
    {
        //
    }

    public function handle(LoanDeleted $event)
    {
        foreach ($event->loan->items as $item) {
            $item->delete();
        }

        foreach ($event->loan->payments as $payment) {
            $payment->delete();
        }

        foreach ($event->loan->activities as $activity) {
            $activity->delete();
        }

        foreach ($event->loan->mailQueue as $mailQueue) {
            $mailQueue->delete();
        }

        foreach ($event->loan->notes as $note) {
            $note->delete();
        }

        $event->loan->custom()->delete();
        $event->loan->amount()->delete();

        Invest::where('loan_id', $event->loan->id)->update(['loan_id' => 0]);

        Expense::where('loan_id', $event->loan->id)->update(['loan_id' => 0]);

        $group = Group::where('id', $event->loan->group_id)
            ->where('last_number', $event->loan->number)
            ->first();

        if ($group) {
            $group->next_id = $group->next_id - 1;
            $group->save();
        }
    }
}
