<?php

namespace FI\Events\Listeners;

use FI\Events\InvestRejected;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Support\Parser;

class InvestRejectedListener
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function handle(InvestRejected $event)
    {
        $event->invest->activities()->create(['activity' => 'public.rejected']);

        $parser = new Parser($event->invest);

        $mail = $this->mailQueue->create($event->invest, [
            'to' => [$event->invest->user->email],
            'cc' => [config('fi.mailDefaultCc')],
            'bcc' => [config('fi.mailDefaultBcc')],
            'subject' => trans('fi.invest_status_change_notification'),
            'body' => $parser->parse('investRejectedEmailBody'),
            'attach_pdf' => config('fi.attachPdf'),
        ]);

        $this->mailQueue->send($mail->id);
    }
}
