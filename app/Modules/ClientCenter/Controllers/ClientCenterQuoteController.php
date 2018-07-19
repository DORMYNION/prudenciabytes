<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Invests\Models\Invest;
use FI\Support\Statuses\InvestStatuses;
use Illuminate\Support\Facades\DB;

class ClientCenterInvestController extends Controller
{
    private $investStatuses;

    public function __construct(InvestStatuses $investStatuses)
    {
        $this->investStatuses = $investStatuses;
    }

    public function index()
    {
        $invests = Invest::with(['amount.invest.currency', 'client'])
            ->where('client_id', auth()->user()->client->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy(DB::raw('length(number)'), 'DESC')
            ->orderBy('number', 'DESC')
            ->paginate(config('fi.resultsPerPage'));

        return view('client_center.invests.index')
            ->with('invests', $invests)
            ->with('investStatuses', $this->investStatuses->statuses());
    }
}