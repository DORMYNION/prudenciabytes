<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Events\LoanViewed;
use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Merchant\Support\MerchantFactory;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\LoanStatuses;

class ClientCenterPublicLoanController extends Controller
{
    public function show($urlKey)
    {
        $loan = Loan::where('url_key', $urlKey)->first();

        app()->setLocale($loan->client->language);

        event(new LoanViewed($loan));

        return view('client_center.loans.public')
            ->with('loan', $loan)
            ->with('statuses', LoanStatuses::statuses())
            ->with('urlKey', $urlKey)
            ->with('merchantDrivers', MerchantFactory::getDrivers(true))
            ->with('attachments', $loan->clientAttachments);
    }

    public function pdf($urlKey)
    {
        $loan = Loan::with('items.taxRate', 'items.taxRate2', 'items.amount.item.loan', 'items.loan')
            ->where('url_key', $urlKey)->first();

        event(new LoanViewed($loan));

        $pdf = PDFFactory::create();

        $pdf->download($loan->html, FileNames::loan($loan));
    }

    public function html($urlKey)
    {
        $loan = Loan::with('items.taxRate', 'items.taxRate2', 'items.amount.item.loan', 'items.loan')
            ->where('url_key', $urlKey)->first();

        return $loan->html;
    }
}