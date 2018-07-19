<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\RecurringLoans\Support;

use FI\Modules\RecurringLoans\Models\RecurringLoan;
use FI\Modules\RecurringLoans\Models\RecurringLoanAmount;
use FI\Modules\RecurringLoans\Models\RecurringLoanItem;
use FI\Modules\RecurringLoans\Models\RecurringLoanItemAmount;

class RecurringLoanCalculate
{
    public function calculateAll()
    {
        $recurringLoanIds = RecurringLoan::select('id')->get();

        foreach ($recurringLoanIds as $recurringLoanId) {
            $this->calculate($recurringLoanId->id);
        }
    }

    public function calculate($recurringLoanId)
    {
        $recurringLoan = RecurringLoan::find($recurringLoanId);

        $recurringLoanItems = RecurringLoanItem::select('recurring_loan_items.*',
            'tax_rates_1.percent AS tax_rate_1_percent',
            'tax_rates_2.percent AS tax_rate_2_percent',
            'tax_rates_2.is_compound AS tax_rate_2_is_compound',
            'tax_rates_1.calculate_vat AS tax_rate_1_calculate_vat')
            ->leftJoin('tax_rates AS tax_rates_1', 'recurring_loan_items.tax_rate_id', '=', 'tax_rates_1.id')
            ->leftJoin('tax_rates AS tax_rates_2', 'recurring_loan_items.tax_rate_2_id', '=', 'tax_rates_2.id')
            ->where('recurring_loan_id', $recurringLoanId)
            ->get();

        $calculator = new RecurringLoanCalculator;
        $calculator->setId($recurringLoanId);
        $calculator->setDiscount($recurringLoan->discount);

        foreach ($recurringLoanItems as $recurringLoanItem) {
            $taxRatePercent = ($recurringLoanItem->tax_rate_id) ? $recurringLoanItem->tax_rate_1_percent : 0;
            $taxRate2Percent = ($recurringLoanItem->tax_rate_2_id) ? $recurringLoanItem->tax_rate_2_percent : 0;
            $taxRate2IsCompound = ($recurringLoanItem->tax_rate_2_is_compound) ? 1 : 0;
            $taxRate1CalculateVat = ($recurringLoanItem->tax_rate_1_calculate_vat) ? 1 : 0;

            $calculator->addItem($recurringLoanItem->id, $recurringLoanItem->quantity, $recurringLoanItem->price, $taxRatePercent, $taxRate2Percent, $taxRate2IsCompound, $taxRate1CalculateVat);
        }

        $calculator->calculate();

        // Get the calculated values
        $calculatedItemAmounts = $calculator->getCalculatedItemAmounts();
        $calculatedAmount = $calculator->getCalculatedAmount();

        // Update the item amount records
        foreach ($calculatedItemAmounts as $calculatedItemAmount) {
            $recurringLoanItemAmount = RecurringLoanItemAmount::firstOrNew(['item_id' => $calculatedItemAmount['item_id']]);
            $recurringLoanItemAmount->fill($calculatedItemAmount);
            $recurringLoanItemAmount->save();
        }

        // Update the overall recurringLoan amount record
        $recurringLoanAmount = RecurringLoanAmount::firstOrNew(['recurring_loan_id' => $recurringLoanId]);
        $recurringLoanAmount->fill($calculatedAmount);
        $recurringLoanAmount->save();
    }
}