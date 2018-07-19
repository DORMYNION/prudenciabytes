<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Reports;

use FI\Modules\Payments\Models\Payment;
use FI\Support\CurrencyFormatter;

class RevenueByClientReport
{
    public function getResults($companyProfileId = null, $year)
    {
        $results = [];

        $payments = Payment::select('payments.*')
            ->with(['loan.client'])
            ->year($year)
            ->join('loans', 'loans.id', '=', 'payments.loan_id')
            ->join('clients', 'clients.id', '=', 'loans.client_id')
            ->orderBy('clients.name');

        if ($companyProfileId) {
            $payments->where('company_profile_id', $companyProfileId);
        }

        $payments = $payments->get();

        foreach ($payments as $payment) {
            if (isset($results[$payment->loan->client->name]['months'][date('n', strtotime($payment->paid_at))])) {
                $results[$payment->loan->client->name]['months'][date('n', strtotime($payment->paid_at))] += $payment->amount / $payment->loan->exchange_rate;
            } else {
                $results[$payment->loan->client->name]['months'][date('n', strtotime($payment->paid_at))] = $payment->amount / $payment->loan->exchange_rate;
            }
        }

        foreach ($results as $client => $result) {
            $results[$client]['total'] = 0;

            foreach (range(1, 12) as $month) {
                if (!isset($results[$client]['months'][$month])) {
                    $results[$client]['months'][$month] = CurrencyFormatter::format(0);
                } else {
                    $results[$client]['total'] += $results[$client]['months'][$month];
                    $results[$client]['months'][$month] = CurrencyFormatter::format($results[$client]['months'][$month]);
                }
            }
            $results[$client]['total'] = CurrencyFormatter::format($results[$client]['total']);
        }

        return $results;
    }
}