<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Exports\Support\Results;

use FI\Modules\Invests\Models\InvestItem;

class InvestItems implements SourceInterface
{
    public function getResults($params = [])
    {
        $investItem = InvestItem::select('invests.number', 'invest_items.created_at', 'invest_items.name',
            'invest_items.description', 'invest_items.quantity', 'invest_items.price', 'tax_rate_1.name AS tax_rate_1_name',
            'tax_rate_1.percent AS tax_rate_1_percent', 'tax_rate_1.is_compound AS tax_rate_1_is_compound',
            'invest_item_amounts.tax_1 AS tax_rate_1_amount', 'tax_rate_2.name AS tax_rate_2_name',
            'tax_rate_2.percent AS tax_rate_2_percent', 'tax_rate_2.is_compound AS tax_rate_2_is_compound',
            'invest_item_amounts.tax_2 AS tax_rate_2_amount', 'invest_item_amounts.subtotal', 'invest_item_amounts.tax',
            'invest_item_amounts.total')
            ->join('invests', 'invests.id', '=', 'invest_items.invest_id')
            ->join('invest_item_amounts', 'invest_item_amounts.item_id', '=', 'invest_items.id')
            ->leftJoin('tax_rates AS tax_rate_1', 'tax_rate_1.id', '=', 'invest_items.tax_rate_id')
            ->leftJoin('tax_rates AS tax_rate_2', 'tax_rate_2.id', '=', 'invest_items.tax_rate_2_id')
            ->orderBy('invests.number');

        return $investItem->get()->toArray();
    }
}