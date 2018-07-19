<?php

namespace FI\Modules\Invests\Support;

use FI\Modules\Invests\Models\Invest;
use FI\Modules\Invests\Models\InvestAmount;
use FI\Modules\Invests\Models\InvestItem;
use FI\Modules\Invests\Models\InvestItemAmount;

class InvestCalculate
{
    public function calculateAll()
    {
        foreach (Invest::get() as $invest) {
            $this->calculate($invest);
        }
    }

    public function calculate($invest)
    {
        $investItems = InvestItem::select('invest_items.*',
            'tax_rates_1.percent AS tax_rate_1_percent',
            'tax_rates_2.percent AS tax_rate_2_percent',
            'tax_rates_2.is_compound AS tax_rate_2_is_compound',
            'tax_rates_1.calculate_vat AS tax_rate_1_calculate_vat')
            ->leftJoin('tax_rates AS tax_rates_1', 'invest_items.tax_rate_id', '=', 'tax_rates_1.id')
            ->leftJoin('tax_rates AS tax_rates_2', 'invest_items.tax_rate_2_id', '=', 'tax_rates_2.id')
            ->where('invest_id', $invest->id)
            ->get();

        $calculator = new InvestCalculator;
        $calculator->setId($invest->id);
        $calculator->setDiscount($invest->discount);

        foreach ($investItems as $investItem) {
            $taxRatePercent = ($investItem->tax_rate_id) ? $investItem->tax_rate_1_percent : 0;
            $taxRate2Percent = ($investItem->tax_rate_2_id) ? $investItem->tax_rate_2_percent : 0;
            $taxRate2IsCompound = ($investItem->tax_rate_2_is_compound) ? 1 : 0;
            $taxRate1CalculateVat = ($investItem->tax_rate_1_calculate_vat) ? 1 : 0;

            $calculator->addItem($investItem->id, $investItem->quantity, $investItem->price, $taxRatePercent, $taxRate2Percent, $taxRate2IsCompound, $taxRate1CalculateVat);
        }

        $calculator->calculate();

        // Get the calculated values
        $calculatedItemAmounts = $calculator->getCalculatedItemAmounts();
        $calculatedAmount = $calculator->getCalculatedAmount();

        // Update the item amount records
        foreach ($calculatedItemAmounts as $calculatedItemAmount) {
            $investItemAmount = InvestItemAmount::firstOrNew(['item_id' => $calculatedItemAmount['item_id']]);
            $investItemAmount->fill($calculatedItemAmount);
            $investItemAmount->save();
        }

        // Update the overall invest amount record
        $investAmount = InvestAmount::firstOrNew(['invest_id' => $invest->id]);
        $investAmount->fill($calculatedAmount);
        $investAmount->save();
    }
}
