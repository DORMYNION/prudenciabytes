<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Reports;

use FI\Modules\Expenses\Models\Expense;
use FI\Modules\Loans\Models\Loan;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\NumberFormatter;
use FI\Support\Statuses\LoanStatuses;

class TaxSummaryReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null, $excludeUnpaidLoans = 0)
    {
        $results = [
            'from_date' => DateFormatter::format($fromDate),
            'to_date' => DateFormatter::format($toDate),
            'total' => 0,
            'paid' => 0,
            'remaining' => 0,
            'records' => [],
        ];

        $loans = Loan::with(['items.taxRate', 'items.taxRate2', 'items.amount'])
            ->where('loan_date', '>=', $fromDate)
            ->where('loan_date', '<=', $toDate)
            ->where('loan_status_id', '<>', LoanStatuses::getStatusId('canceled'));

        $expenseTax = (Expense::where('expense_date', '>=', $fromDate)
            ->where('expense_date', '<=', $toDate)
            ->sum('tax')) ?: 0;

        if ($companyProfileId) {
            $loans->where('company_profile_id', $companyProfileId);
        }

        if ($excludeUnpaidLoans) {
            $loans->paid();
        }

        $loans = $loans->get();

        foreach ($loans as $loan) {
            foreach ($loan->items as $loanItem) {
                if ($loanItem->tax_rate_id) {
                    $key = $loanItem->taxRate->name . ' (' . NumberFormatter::format($loanItem->taxRate->percent, null, 3) . '%)';

                    if (isset($results['records'][$key]['taxable_amount'])) {
                        $results['records'][$key]['taxable_amount'] += $loanItem->amount->subtotal / $loan->exchange_rate;
                        $results['records'][$key]['taxes'] += $loanItem->amount->tax_1 / $loan->exchange_rate;
                    } else {
                        $results['records'][$key]['taxable_amount'] = $loanItem->amount->subtotal / $loan->exchange_rate;
                        $results['records'][$key]['taxes'] = $loanItem->amount->tax_1 / $loan->exchange_rate;
                    }
                }

                if ($loanItem->tax_rate_2_id) {
                    $key = $loanItem->taxRate2->name . ' (' . NumberFormatter::format($loanItem->taxRate2->percent, null, 3) . '%)';

                    if (isset($results['records'][$key]['taxable_amount'])) {
                        if ($loanItem->taxRate2->is_compound) {
                            $results['records'][$key]['taxable_amount'] += ($loanItem->amount->subtotal + $loanItem->amount->tax_1) / $loan->exchange_rate;
                        } else {
                            $results['records'][$key]['taxable_amount'] += $loanItem->amount->subtotal / $loan->exchange_rate;
                        }

                        $results['records'][$key]['taxes'] += $loanItem->amount->tax_2 / $loan->exchange_rate;
                    } else {
                        if ($loanItem->taxRate2->is_compound) {
                            $results['records'][$key]['taxable_amount'] = ($loanItem->amount->subtotal + $loanItem->amount->tax_2) / $loan->exchange_rate;
                        } else {
                            $results['records'][$key]['taxable_amount'] = $loanItem->amount->subtotal / $loan->exchange_rate;
                        }

                        $results['records'][$key]['taxes'] = $loanItem->amount->tax_2 / $loan->exchange_rate;
                    }
                }
            }
        }

        foreach ($results['records'] as $key => $result) {
            $results['total'] = $results['total'] + $result['taxes'];
            $results['records'][$key]['taxable_amount'] = CurrencyFormatter::format($result['taxable_amount']);
            $results['records'][$key]['taxes'] = CurrencyFormatter::format($result['taxes']);
        }

        $results['paid'] = CurrencyFormatter::format($expenseTax);
        $results['remaining'] = CurrencyFormatter::format($results['total'] - $expenseTax);
        $results['total'] = CurrencyFormatter::format($results['total']);

        return $results;
    }
}