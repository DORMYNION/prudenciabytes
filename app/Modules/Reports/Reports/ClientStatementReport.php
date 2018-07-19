<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Reports;

use FI\Modules\Clients\Models\Client;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;

class ClientStatementReport
{
    public function getResults($clientName, $fromDate, $toDate, $companyProfileId = null)
    {
        $results = [
            'client_name' => '',
            'from_date' => '',
            'to_date' => '',
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 0,
            'paid' => 0,
            'balance' => 0,
            'records' => [],
        ];

        $client = Client::where('unique_name', $clientName)->first();

        $loans = $client->loans()
            ->with('items', 'client.currency', 'amount.loan.currency')
            ->notCanceled()
            ->where('loan_date', '>=', $fromDate)
            ->where('loan_date', '<=', $toDate)
            ->orderBy('loan_date');

        if ($companyProfileId) {
            $loans->where('company_profile_id', $companyProfileId);
        }

        $loans = $loans->get();

        foreach ($loans as $loan) {
            $results['records'][] = [
                'formatted_loan_date' => $loan->formatted_loan_date,
                'number' => $loan->number,
                'summary' => $loan->summary,
                'subtotal' => $loan->amount->subtotal,
                'discount' => $loan->amount->discount,
                'tax' => $loan->amount->tax,
                'total' => $loan->amount->total,
                'paid' => $loan->amount->paid,
                'balance' => $loan->amount->balance,
                'formatted_subtotal' => $loan->amount->formatted_subtotal,
                'formatted_discount' => $loan->amount->formatted_discount,
                'formatted_tax' => $loan->amount->formatted_tax,
                'formatted_total' => $loan->amount->formatted_total,
                'formatted_paid' => $loan->amount->formatted_paid,
                'formatted_balance' => $loan->amount->formatted_balance,
            ];

            $results['subtotal'] += $loan->amount->subtotal;
            $results['discount'] += $loan->amount->discount;
            $results['tax'] += $loan->amount->tax;
            $results['total'] += $loan->amount->total;
            $results['paid'] += $loan->amount->paid;
            $results['balance'] += $loan->amount->balance;
        }

        $currency = $client->currency;

        $results['client_name'] = $client->name;
        $results['from_date'] = DateFormatter::format($fromDate);
        $results['to_date'] = DateFormatter::format($toDate);
        $results['subtotal'] = CurrencyFormatter::format($results['subtotal'], $currency);
        $results['discount'] = CurrencyFormatter::format($results['discount'], $currency);
        $results['tax'] = CurrencyFormatter::format($results['tax'], $currency);
        $results['total'] = CurrencyFormatter::format($results['total'], $currency);
        $results['paid'] = CurrencyFormatter::format($results['paid'], $currency);
        $results['balance'] = CurrencyFormatter::format($results['balance'], $currency);

        return $results;
    }
}