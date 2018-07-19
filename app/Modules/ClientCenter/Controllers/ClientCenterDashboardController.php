<?php

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Payments\Models\Payment;
use FI\Modules\Invests\Models\Invest;
use FI\Support\Statuses\LoanStatuses;
use FI\Support\Statuses\InvestStatuses;
use Illuminate\Support\Facades\DB;

class ClientCenterDashboardController extends Controller
{
    private $loanStatuses;
    private $investStatuses;

    public function __construct(
        LoanStatuses $loanStatuses,
        InvestStatuses $investStatuses)
    {
        $this->loanStatuses = $loanStatuses;
        $this->investStatuses = $investStatuses;
    }

    public function index()
    {
        $clientId = auth()->user()->client->id;

        $loans = Loan::with(['amount.loan.currency', 'client'])
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'DESC')
            ->orderBy(DB::raw('length(number)'), 'DESC')
            ->orderBy('number', 'DESC')
            ->limit(5)->get();

        $invests = Invest::with(['amount.invest.currency', 'client'])
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'DESC')
            ->orderBy(DB::raw('length(number)'), 'DESC')
            ->orderBy('number', 'DESC')
            ->limit(5)->get();

        $payments = Payment::with('loan.amount.loan.currency', 'loan.client')
            ->whereHas('loan', function ($loan) use ($clientId) {
                $loan->where('client_id', $clientId);
            })->orderBy('created_at', 'desc')
            ->limit(5)->get();

        return view('client_center.index')
            ->with('loans', $loans)
            ->with('invests', $invests)
            ->with('payments', $payments)
            ->with('loanStatuses', $this->loanStatuses->statuses())
            ->with('investStatuses', $this->investStatuses->statuses());
    }

    public function redirectToLogin()
    {
        return redirect()->route('session.login');
    }
}
