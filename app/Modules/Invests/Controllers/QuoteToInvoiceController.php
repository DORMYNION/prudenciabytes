<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Invests\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Invests\Models\Invest;
use FI\Modules\Invests\Requests\InvestToLoanRequest;
use FI\Modules\Invests\Support\InvestToLoan;
use FI\Support\DateFormatter;

class InvestToLoanController extends Controller
{
    private $investToLoan;

    public function __construct(InvestToLoan $investToLoan)
    {
        $this->investToLoan = $investToLoan;
    }

    public function create()
    {
        return view('invests._modal_invest_to_loan')
            ->with('invest_id', request('invest_id'))
            ->with('client_id', request('client_id'))
            ->with('groups', Group::getList())
            ->with('user_id', auth()->user()->id)
            ->with('loan_date', DateFormatter::format());
    }

    public function store(InvestToLoanRequest $request)
    {
        $invest = Invest::find($request->input('invest_id'));

        $loan = $this->investToLoan->convert(
            $invest,
            DateFormatter::unformat($request->input('loan_date')),
            DateFormatter::incrementDateByDays(DateFormatter::unformat($request->input('loan_date')), config('fi.loansDueAfter')),
            $request->input('group_id')
        );

        return response()->json(['redirectTo' => route('loans.edit', ['loan' => $loan->id])], 200);
    }
}