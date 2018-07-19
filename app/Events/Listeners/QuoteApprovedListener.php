<?php

namespace FI\Events\Listeners;

use FI\Events\InvestApproved;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Modules\Invests\Support\InvestToLoan;
use FI\Support\DateFormatter;
use FI\Support\Parser;

class InvestApprovedListener
{
    public function __construct(MailQueue $mailQueue, InvestToLoan $investToLoan)
    {
        $this->mailQueue = $mailQueue;
        $this->investToLoan = $investToLoan;
    }

    public function handle(InvestApproved $event)
    {
        // Create the activity record
        $event->invest->activities()->create(['activity' => 'public.approved']);

        // If applicable, convert the invest to an loan when invest is approved
        if (config('fi.convertInvestWhenApproved')) {
            $this->investToLoan->convert(
                $event->invest,
                date('Y-m-d'),
                DateFormatter::incrementDateByDays(date('Y-m-d'), config('fi.loansDueAfter')),
                config('fi.loanGroup')
            );
        }

        $parser = new Parser($event->invest);

        $mail = $this->mailQueue->create($event->invest, [
            'to' => [$event->invest->user->email],
            'cc' => [config('fi.mailDefaultCc')],
            'bcc' => [config('fi.mailDefaultBcc')],
            'subject' => trans('fi.invest_status_change_notification'),
            'body' => $parser->parse('investApprovedEmailBody'),
            'attach_pdf' => config('fi.attachPdf'),
        ]);

        $this->mailQueue->send($mail->id);
    }
}
