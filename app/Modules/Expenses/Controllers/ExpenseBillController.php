<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Expenses\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Expenses\Models\Expense;
use FI\Modules\Expenses\Requests\ExpenseBillRequest;
use FI\Modules\Loans\Models\LoanItem;

class ExpenseBillController extends Controller
{
    public function create()
    {
        $expense = Expense::defaultQuery()->find(request('id'));

        $clientLoans = $expense->client->loans()->orderBy('created_at', 'desc')->statusIn([
            'draft',
            'sent',
        ])->get();

        $loans = [];

        foreach ($clientLoans as $loan) {
            $loans[$loan->id] = $loan->formatted_created_at . ' - ' . $loan->number . ' ' . $loan->summary;
        }

        return view('expenses._modal_bill')
            ->with('expense', $expense)
            ->with('loans', $loans)
            ->with('redirectTo', request('redirectTo'));
    }

    public function store(ExpenseBillRequest $request)
    {
        $expense = Expense::find(request('id'));

        $expense->loan_id = request('loan_id');

        $expense->save();

        if (request('add_line_item')) {
            $item = [
                'loan_id' => request('loan_id'),
                'name' => request('item_name'),
                'description' => request('item_description'),
                'quantity' => 1,
                'price' => $expense->amount,
            ];

            LoanItem::create($item);
        }
    }
}