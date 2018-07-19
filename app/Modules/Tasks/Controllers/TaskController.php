<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Tasks\Controllers;

use Carbon\Carbon;
use FI\Events\LoanCreatedRecurring;
use FI\Events\OverdueNoticeEmailed;
use FI\Http\Controllers\Controller;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Models\LoanItem;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Modules\RecurringLoans\Models\RecurringLoan;
use FI\Support\DateFormatter;
use FI\Support\Parser;
use FI\Support\Statuses\LoanStatuses;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    private $mailQueue;

    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function run()
    {
        $this->queueOverdueLoans();

        $this->queueUpcomingLoans();

        $this->recurLoans();
    }

    private function queueOverdueLoans()
    {
        $days = config('fi.overdueLoanReminderFrequency');

        if ($days) {
            $days = explode(',', $days);

            foreach ($days as $daysAgo) {
                $daysAgo = trim($daysAgo);

                if (is_numeric($daysAgo)) {
                    $daysAgo = intval($daysAgo);

                    $date = Carbon::now()->subDays($daysAgo)->format('Y-m-d');

                    $loans = Loan::with('client')
                        ->where('loan_status_id', '=', LoanStatuses::getStatusId('sent'))
                        ->whereHas('amount', function ($query) {
                            $query->where('balance', '>', '0');
                        })
                        ->where('due_at', $date)
                        ->get();

                    Log::info('FI::MailQueue - Loans found due ' . $daysAgo . ' days ago on ' . $date . ': ' . $loans->count());

                    foreach ($loans as $loan) {
                        $parser = new Parser($loan);

                        $mail = $this->mailQueue->create($loan, [
                            'to' => [$loan->client->email],
                            'cc' => [config('fi.mailDefaultCc')],
                            'bcc' => [config('fi.mailDefaultBcc')],
                            'subject' => $parser->parse('overdueLoanEmailSubject'),
                            'body' => $parser->parse('overdueLoanEmailBody'),
                            'attach_pdf' => config('fi.attachPdf'),
                        ]);

                        $this->mailQueue->send($mail->id);

                        event(new OverdueNoticeEmailed($loan, $mail));
                    }
                } else {
                    Log::info('FI::MailQueue - Invalid overdue indicator: ' . $daysAgo);
                }
            }
        }
    }

    private function queueUpcomingLoans()
    {
        $days = config('fi.upcomingPaymentNoticeFrequency');

        if ($days) {
            $days = explode(',', $days);

            foreach ($days as $daysFromNow) {
                $daysFromNow = trim($daysFromNow);

                if (is_numeric($daysFromNow)) {
                    $daysFromNow = intval($daysFromNow);

                    $date = Carbon::now()->addDays($daysFromNow)->format('Y-m-d');

                    $loans = Loan::with('client')
                        ->where('loan_status_id', '=', LoanStatuses::getStatusId('sent'))
                        ->whereHas('amount', function ($query) {
                            $query->where('balance', '>', '0');
                        })
                        ->where('due_at', $date)
                        ->get();

                    Log::info('FI::MailQueue - Loans found due ' . $daysFromNow . ' days from now on ' . $date . ': ' . $loans->count());

                    foreach ($loans as $loan) {
                        $parser = new Parser($loan);

                        $mail = $this->mailQueue->create($loan, [
                            'to' => [$loan->client->email],
                            'cc' => [config('fi.mailDefaultCc')],
                            'bcc' => [config('fi.mailDefaultBcc')],
                            'subject' => $parser->parse('upcomingPaymentNoticeEmailSubject'),
                            'body' => $parser->parse('upcomingPaymentNoticeEmailBody'),
                            'attach_pdf' => config('fi.attachPdf'),
                        ]);

                        $this->mailQueue->send($mail->id);
                    }
                } else {
                    Log::info('FI::MailQueue - Upcoming payment due indicator: ' . $daysFromNow);
                }
            }
        }
    }

    private function recurLoans()
    {
        $recurringLoans = RecurringLoan::recurNow()->get();

        foreach ($recurringLoans as $recurringLoan) {
            $loanData = [
                'company_profile_id' => $recurringLoan->company_profile_id,
                'created_at' => $recurringLoan->next_date,
                'group_id' => $recurringLoan->group_id,
                'user_id' => $recurringLoan->user_id,
                'client_id' => $recurringLoan->client_id,
                'currency_code' => $recurringLoan->currency_code,
                'template' => $recurringLoan->template,
                'terms' => $recurringLoan->terms,
                'footer' => $recurringLoan->footer,
                'summary' => $recurringLoan->summary,
                'discount' => $recurringLoan->discount,
            ];

            $loan = Loan::create($loanData);

            CustomField::copyCustomFieldValues($recurringLoan, $loan);

            foreach ($recurringLoan->recurringLoanItems as $item) {
                $itemData = [
                    'loan_id' => $loan->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'tax_rate_id' => $item->tax_rate_id,
                    'tax_rate_2_id' => $item->tax_rate_2_id,
                    'display_order' => $item->display_order,
                ];

                LoanItem::create($itemData);
            }

            if ($recurringLoan->stop_date == '0000-00-00' or ($recurringLoan->stop_date !== '0000-00-00' and ($recurringLoan->next_date < $recurringLoan->stop_date))) {
                $nextDate = DateFormatter::incrementDate(substr($recurringLoan->next_date, 0, 10), $recurringLoan->recurring_period, $recurringLoan->recurring_frequency);
            } else {
                $nextDate = '0000-00-00';
            }

            $recurringLoan->next_date = $nextDate;
            $recurringLoan->save();

            event(new LoanCreatedRecurring($loan, $recurringLoan));
        }

        return count($recurringLoans);
    }
}