<?php

namespace FI\Events\Listeners;

use FI\Events\InvestDeleted;
use FI\Modules\Groups\Models\Group;

class InvestDeletedListener
{
    public function __construct()
    {
        //
    }

    public function handle(InvestDeleted $event)
    {
        foreach ($event->invest->items as $item) {
            $item->delete();
        }

        foreach ($event->invest->activities as $activity) {
            $activity->delete();
        }

        foreach ($event->invest->mailQueue as $mailQueue) {
            $mailQueue->delete();
        }

        foreach ($event->invest->notes as $note) {
            $note->delete();
        }

        $event->invest->custom()->delete();
        $event->invest->amount()->delete();

        $group = Group::where('id', $event->invest->group_id)
            ->where('last_number', $event->invest->number)
            ->first();

        if ($group) {
            $group->next_id = $group->next_id - 1;
            $group->save();
        }
    }
}
