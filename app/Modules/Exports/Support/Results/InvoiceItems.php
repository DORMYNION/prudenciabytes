<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Exports\Support\Results;

use FI\Modules\Loans\Models\LoanItem;

class LoanItems implements SourceInterface
{
    public function getResults($params = [])
    {
        $loanItem = LoanItem::select('loans.number', 'loan_items.created_at', 'loan_items.name',
            'loan_items.description', 'loan_items.quantity', 'loan_items.price', 'tax_rate_1.name AS tax_rate_1_name',
            'tax_rate_1.percent AS tax_rate_1_percent', 'tax_rate_1.is_compound AS tax_rate_1_is_compound',
            'loan_item_amounts.tax_1 AS tax_rate_1_amount', 'tax_rate_2.name AS tax_rate_2_name',
            'tax_rate_2.percent AS tax_rate_2_percent', 'tax_rate_2.is_compound AS tax_rate_2_is_compound',
            'loan_item_amounts.tax_2 AS tax_rate_2_amount', 'loan_item_amounts.subtotal', 'loan_item_amounts.tax',
            'loan_item_amounts.total')
            ->join('loans', 'loans.id', '=', 'loan_items.loan_id')
            ->join('loan_item_amounts', 'loan_item_amounts.item_id', '=', 'loan_items.id')
            ->leftJoin('tax_rates AS tax_rate_1', 'tax_rate_1.id', '=', 'loan_items.tax_rate_id')
            ->leftJoin('tax_rates AS tax_rate_2', 'tax_rate_2.id', '=', 'loan_items.tax_rate_2_id')
            ->orderBy('loans.number');

        return $loanItem->get()->toArray();
    }
}