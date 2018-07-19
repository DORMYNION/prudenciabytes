<?php

namespace FI\Events\Listeners;

use FI\Events\LoanCreatedRecurring;
use FI\Events\LoanEmailed;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Support\Parser;

class LoanCreatedRecurringListener
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function handle(LoanCreatedRecurring $event)
    {
        if (config('fi.automaticEmailOnRecur') and $event->loan->client->email) {
            $parser = new Parser($event->loan);

            if (!$event->loan->is_overdue) {
                $subject = $parser->parse('loanEmailSubject');
                $body = $parser->parse('loanEmailBody');
            } else {
                $subject = $parser->parse('overdueLoanEmailSubject');
                $body = $parser->parse('overdueLoanEmailBody');
            }

            $mail = $this->mailQueue->create($event->loan, [
                'to' => [$event->loan->client->email],
                'cc' => [config('fi.mailDefaultCc')],
                'bcc' => [config('fi.mailDefaultBcc')],
                'subject' => $subject,
                'body' => $body,
                'attach_pdf' => config('fi.attachPdf'),
            ]);

            $this->mailQueue->send($mail->id);

            event(new LoanEmailed($event->loan));
        }
    }
}
