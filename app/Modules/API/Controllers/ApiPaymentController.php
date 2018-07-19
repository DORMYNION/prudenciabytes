<?php

namespace FI\Modules\API\Controllers;

use FI\Modules\Loans\Models\Loan;
use FI\Modules\Payments\Models\Payment;
use FI\Modules\Payments\Requests\PaymentRequest;

class ApiPaymentController extends ApiController
{
    public function lists()
    {
        $payments = Payment::select('payments.*')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id')
            ->clientId(request('client_id'))
            ->loanId(request('loan_id'))
            ->loanNumber(request('loan_number'))
            ->sortable(['paid_at' => 'desc', 'payments.created_at' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return response()->json($payments);
    }

    public function show()
    {
        return response()->json(Payment::find(request('id')));
    }

    public function store(PaymentRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        if (!Loan::find($input['loan_id'])) {
            return response()->json([trans('fi.record_not_found')], 400);
        }

        $payment = Payment::create($input);

        return response()->json($payment);
    }

    public function delete()
    {
        $validator = $this->validator->make(request()->only(['id']), ['id' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 400);
        }

        if (Payment::find(request('id'))) {
            Payment::destroy(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}
