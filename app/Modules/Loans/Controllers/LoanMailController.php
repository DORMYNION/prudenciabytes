<?php

namespace FI\Modules\Loans\Controllers;

use FI\Events\LoanEmailed;
use FI\Events\LoanEmailing;
use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Requests\SendEmailRequest;
use FI\Support\Contacts;
use FI\Support\Parser;

class LoanMailController extends Controller
{
    private $mailQueue;

    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function create()
    {
        $loan = Loan::find(request('loan_id'));

        $contacts = new Contacts($loan->client);

        $parser = new Parser($loan);

        if (!$loan->is_overdue) {
            $subject = $parser->parse('loanEmailSubject');
            $body = $parser->parse('loanEmailBody');
        } else {
            $subject = $parser->parse('overdueLoanEmailSubject');
            $body = $parser->parse('overdueLoanEmailBody');
        }

        return view('loans._modal_mail')
            ->with('loanId', $loan->id)
            ->with('redirectTo', urlencode(request('redirectTo')))
            ->with('subject', $subject)
            ->with('body', $body)
            ->with('contactDropdownTo', $contacts->contactDropdownTo())
            ->with('contactDropdownCc', $contacts->contactDropdownCc())
            ->with('contactDropdownBcc', $contacts->contactDropdownBcc());
    }

    public function store(SendEmailRequest $request)
    {
        $loan = Loan::find($request->input('loan_id'));

        event(new LoanEmailing($loan));

        $mail = $this->mailQueue->create($loan, $request->except('loan_id'));

        if ($this->mailQueue->send($mail->id)) {
            event(new LoanEmailed($loan));
        } else {
            return response()->json(['errors' => [[$this->mailQueue->getError()]]], 400);
        }
    }
}
