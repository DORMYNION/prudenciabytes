<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Events\InvestApproved;
use FI\Events\InvestRejected;
use FI\Events\InvestViewed;
use FI\Http\Controllers\Controller;
use FI\Modules\Invests\Models\Invest;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\InvestStatuses;

class ClientCenterPublicInvestController extends Controller
{
    public function show($urlKey)
    {
        $invest = Invest::where('url_key', $urlKey)->first();

        app()->setLocale($invest->client->language);

        event(new InvestViewed($invest));

        return view('client_center.invests.public')
            ->with('invest', $invest)
            ->with('statuses', InvestStatuses::statuses())
            ->with('urlKey', $urlKey)
            ->with('attachments', $invest->clientAttachments);
    }

    public function pdf($urlKey)
    {
        $invest = Invest::with('items.taxRate', 'items.taxRate2', 'items.amount.item.invest', 'items.invest')
            ->where('url_key', $urlKey)->first();

        event(new InvestViewed($invest));

        $pdf = PDFFactory::create();

        $pdf->download($invest->html, FileNames::invest($invest));
    }

    public function html($urlKey)
    {
        $invest = Invest::with('items.taxRate', 'items.taxRate2', 'items.amount.item.invest', 'items.invest')
            ->where('url_key', $urlKey)->first();

        return $invest->html;
    }

    public function approve($urlKey)
    {
        $invest = Invest::where('url_key', $urlKey)->first();

        $invest->invest_status_id = InvestStatuses::getStatusId('approved');

        $invest->save();

        event(new InvestApproved($invest));

        return redirect()->route('clientCenter.public.invest.show', [$urlKey]);
    }

    public function reject($urlKey)
    {
        $invest = Invest::where('url_key', $urlKey)->first();

        $invest->invest_status_id = InvestStatuses::getStatusId('rejected');

        $invest->save();

        event(new InvestRejected($invest));

        return redirect()->route('clientCenter.public.invest.show', [$urlKey]);
    }
}