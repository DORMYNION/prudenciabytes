<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Events\InvestEmailed;
use FI\Events\InvestEmailing;
use FI\Http\Controllers\Controller;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Modules\Invests\Models\Invest;
use FI\Requests\SendEmailRequest;
use FI\Support\Contacts;
use FI\Support\Parser;

class InvestMailController extends Controller
{
    private $mailQueue;

    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function create()
    {
        $invest = Invest::find(request('invest_id'));

        $contacts = new Contacts($invest->client);

        $parser = new Parser($invest);

        return view('invests._modal_mail')
            ->with('investId', $invest->id)
            ->with('redirectTo', urlencode(request('redirectTo')))
            ->with('subject', $parser->parse('investEmailSubject'))
            ->with('body', $parser->parse('investEmailBody'))
            ->with('contactDropdownTo', $contacts->contactDropdownTo())
            ->with('contactDropdownCc', $contacts->contactDropdownCc())
            ->with('contactDropdownBcc', $contacts->contactDropdownBcc());
    }

    public function store(SendEmailRequest $request)
    {
        $invest = Invest::find($request->input('invest_id'));

        event(new InvestEmailing($invest));

        $mail = $this->mailQueue->create($invest, $request->except('invest_id'));

        if ($this->mailQueue->send($mail->id)) {
            event(new InvestEmailed($invest));
        } else {
            return response()->json(['errors' => [[$this->mailQueue->getError()]]], 400);
        }
    }
}