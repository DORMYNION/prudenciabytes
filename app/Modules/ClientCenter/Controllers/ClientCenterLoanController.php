<?php

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Models\Loan;
use FI\Support\Statuses\LoanStatuses;
use Illuminate\Support\Facades\DB;

class ClientCenterLoanController extends Controller
{
    private $loanStatuses;

    public function __construct(LoanStatuses $loanStatuses)
    {
        $this->loanStatuses = $loanStatuses;
    }

    public function index()
    {
        $loans = Loan::with(['amount.loan.currency', 'client'])
            ->where('client_id', auth()->user()->client->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy(DB::raw('length(number)'), 'DESC')
            ->orderBy('number', 'DESC')
            ->paginate(config('fi.resultsPerPage'));

        return view('client_center.loans.index')
            ->with('loans', $loans)
            ->with('loanStatuses', $this->loanStatuses->statuses());
    }
}
