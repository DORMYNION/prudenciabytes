<?php

namespace FI\Support\Statuses;

class LoanStatuses extends AbstractStatuses
{
    protected static $statuses = [
        '0' => 'all_statuses',
        '1' => 'draft',
        '2' => 'sent',
        '3' => 'paid',
        '4' => 'canceled',
    ];
}
