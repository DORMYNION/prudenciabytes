<?php

namespace FI\Events;

use FI\Modules\Loans\Models\Loan;
use FI\Modules\MailQueue\Models\MailQueue;
use Illuminate\Queue\SerializesModels;

class OverdueNoticeEmailed extends Event
{
    use SerializesModels;

    public function __construct(Loan $loan, MailQueue $mail)
    {
        $this->loan = $loan;
        $this->mail = $mail;
    }

    public function broadcastOn()
    {
        return [];
    }
}
