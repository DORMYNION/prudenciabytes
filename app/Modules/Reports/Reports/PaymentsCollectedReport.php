<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Reports;

use FI\Modules\Payments\Models\Payment;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;

class PaymentsCollectedReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null)
    {
        $results = [
            'from_date' => DateFormatter::format($fromDate),
            'to_date' => DateFormatter::format($toDate),
            'payments' => [],
            'total' => 0,
        ];

        $payments = Payment::select('payments.*')
            ->with(['loan.client', 'paymentMethod'])
            ->join('loans', 'loans.id', '=', 'payments.loan_id')
            ->dateRange($fromDate, $toDate);

        if ($companyProfileId) {
            $payments->where('loans.company_profile_id', $companyProfileId);
        }

        $payments = $payments->get();

        foreach ($payments as $payment) {
            $results['payments'][] = [
                'client_name' => $payment->loan->client->name,
                'loan_number' => $payment->loan->number,
                'payment_method' => isset($payment->paymentMethod->name) ? $payment->paymentMethod->name : '',
                'note' => $payment->note,
                'date' => $payment->formatted_paid_at,
                'amount' => CurrencyFormatter::format($payment->amount / $payment->loan->exchange_rate),
            ];

            $results['total'] += $payment->amount / $payment->loan->exchange_rate;
        }

        $results['total'] = CurrencyFormatter::format($results['total']);

        return $results;
    }
}