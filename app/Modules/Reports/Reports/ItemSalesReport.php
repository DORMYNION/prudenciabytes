<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Reports;

use FI\Modules\Loans\Models\LoanItem;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\NumberFormatter;
use FI\Support\Statuses\LoanStatuses;

class ItemSalesReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null)
    {
        $results = [
            'from_date' => DateFormatter::format($fromDate),
            'to_date' => DateFormatter::format($toDate),
            'records' => [],
        ];

        $items = LoanItem::byDateRange($fromDate, $toDate)
            ->select('loan_items.name AS item_name', 'loan_items.quantity AS item_quantity',
                'loan_items.price AS item_price', 'clients.name AS client_name', 'loans.number AS loan_number',
                'loans.loan_date AS loan_date', 'loans.exchange_rate AS loan_exchange_rate',
                'loan_item_amounts.subtotal', 'loan_item_amounts.tax', 'loan_item_amounts.total')
            ->join('loans', 'loans.id', '=', 'loan_items.loan_id')
            ->join('loan_item_amounts', 'loan_item_amounts.item_id', '=', 'loan_items.id')
            ->join('clients', 'clients.id', '=', 'loans.client_id')
            ->where('loans.loan_status_id', '<>', LoanStatuses::getStatusId('canceled'))
            ->orderBy('loan_items.name');

        if ($companyProfileId) {
            $items->where('loans.company_profile_id', $companyProfileId);
        }

        $items = $items->get();

        foreach ($items as $item) {
            $results['records'][$item->item_name]['items'][] = [
                'client_name' => $item->client_name,
                'loan_number' => $item->loan_number,
                'date' => DateFormatter::format($item->loan_date),
                'price' => CurrencyFormatter::format($item->item_price / $item->loan_exchange_rate),
                'quantity' => NumberFormatter::format($item->item_quantity),
                'subtotal' => CurrencyFormatter::format($item->subtotal / $item->loan_exchange_rate),
                'tax' => CurrencyFormatter::format($item->tax / $item->loan_exchange_rate),
                'total' => CurrencyFormatter::format($item->total / $item->loan_exchange_rate),
            ];

            if (isset($results['records'][$item->item_name]['totals'])) {
                $results['records'][$item->item_name]['totals']['quantity'] += $item->quantity;
                $results['records'][$item->item_name]['totals']['subtotal'] += round($item->subtotal / $item->loan_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['tax'] += round($item->tax / $item->loan_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['total'] += round($item->total / $item->loan_exchange_rate, 2);
            } else {
                $results['records'][$item->item_name]['totals']['quantity'] = $item->quantity;
                $results['records'][$item->item_name]['totals']['subtotal'] = round($item->subtotal / $item->loan_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['tax'] = round($item->tax / $item->loan_exchange_rate, 2);
                $results['records'][$item->item_name]['totals']['total'] = round($item->total / $item->loan_exchange_rate, 2);
            }
        }

        foreach ($results['records'] as $key => $result) {
            $results['records'][$key]['totals']['quantity'] = NumberFormatter::format($results['records'][$key]['totals']['quantity']);
            $results['records'][$key]['totals']['subtotal'] = CurrencyFormatter::format($results['records'][$key]['totals']['subtotal']);
            $results['records'][$key]['totals']['tax'] = CurrencyFormatter::format($results['records'][$key]['totals']['tax']);
            $results['records'][$key]['totals']['total'] = CurrencyFormatter::format($results['records'][$key]['totals']['total']);
        }

        return $results;
    }
}