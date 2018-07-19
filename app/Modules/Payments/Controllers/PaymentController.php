<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Payments\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\PaymentMethods\Models\PaymentMethod;
use FI\Modules\Payments\Models\Payment;
use FI\Modules\Payments\Requests\PaymentRequest;
use FI\Support\DateFormatter;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::select('payments.*')
            ->with(['loan.client', 'loan.currency', 'paymentMethod'])
            ->join('loans', 'loans.id', '=', 'payments.loan_id')
            ->join('clients', 'clients.id', '=', 'loans.client_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id')
            ->keywords(request('search'))
            ->clientId(request('client'))
            ->sortable(['paid_at' => 'desc', 'length(number)' => 'desc', 'number' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return view('payments.index')
            ->with('payments', $payments)
            ->with('displaySearch', true);
    }

    public function create()
    {
        $date = DateFormatter::format();

        $loan = Loan::find(request('loan_id'));

        return view('payments._modal_enter_payment')
            ->with('loan_id', request('loan_id'))
            ->with('loanNumber', $loan->number)
            ->with('balance', $loan->amount->formatted_numeric_balance)
            ->with('date', $date)
            ->with('paymentMethods', PaymentMethod::getList())
            ->with('client', $loan->client)
            ->with('customFields', CustomField::forTable('payments')->get())
            ->with('redirectTo', request('redirectTo'));
    }

    public function store(PaymentRequest $request)
    {
        $input = $request->except('custom', 'email_payment_receipt');

        $input['paid_at'] = DateFormatter::unformat($input['paid_at']);

        $payment = Payment::create($input);

        $payment->custom->update($request->input('custom', []));

        return response()->json(['success' => true], 200);
    }

    public function edit($id)
    {
        $payment = Payment::find($id);

        return view('payments.form')
            ->with('editMode', true)
            ->with('payment', $payment)
            ->with('paymentMethods', PaymentMethod::getList())
            ->with('loan', $payment->loan)
            ->with('customFields', CustomField::forTable('payments')->get());
    }

    public function update(PaymentRequest $request, $id)
    {
        $input = $request->except('custom');

        $input['paid_at'] = DateFormatter::unformat($input['paid_at']);

        $payment = Payment::find($id);
        $payment->fill($input);
        $payment->save();

        $payment->custom->update($request->input('custom', []));

        return redirect()->route('payments.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        Payment::destroy($id);

        return redirect()->route('payments.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function bulkDelete()
    {
        Payment::destroy(request('ids'));
    }
}