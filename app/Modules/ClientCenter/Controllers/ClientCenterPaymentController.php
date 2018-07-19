<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Payments\Models\Payment;

class ClientCenterPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('loan.amount.loan.currency', 'loan.client')
            ->whereHas('loan', function ($loan) {
                $loan->where('client_id', auth()->user()->client->id);
            })->orderBy('created_at', 'desc')
            ->paginate(config('fi.resultsPerPage'));

        return view('client_center.payments.index')
            ->with('payments', $payments);
    }
}