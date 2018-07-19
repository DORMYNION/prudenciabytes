<?php

namespace FI\Modules\Loans\Support;

use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Models\LoanAmount;
use FI\Modules\Loans\Models\LoanItem;
use FI\Modules\Loans\Models\LoanItemAmount;
use FI\Modules\Payments\Models\Payment;
use FI\Support\Statuses\LoanStatuses;

class LoanCalculate
{
    public function calculateAll()
    {
        foreach (Loan::get() as $loan) {
            $this->calculate($loan);
        }
    }

    public function calculate($loan)
    {
        $loanItems = LoanItem::select('loan_items.*',
            'tax_rates_1.percent AS tax_rate_1_percent',
            'tax_rates_2.percent AS tax_rate_2_percent',
            'tax_rates_2.is_compound AS tax_rate_2_is_compound',
            'tax_rates_1.calculate_vat AS tax_rate_1_calculate_vat')
            ->leftJoin('tax_rates AS tax_rates_1', 'loan_items.tax_rate_id', '=', 'tax_rates_1.id')
            ->leftJoin('tax_rates AS tax_rates_2', 'loan_items.tax_rate_2_id', '=', 'tax_rates_2.id')
            ->where('loan_id', $loan->id)
            ->get();

        $totalPaid = Payment::where('loan_id', $loan->id)->sum('amount');

        $calculator = new LoanCalculator;
        $calculator->setId($loan->id);
        $calculator->setTotalPaid($totalPaid);
        $calculator->setDiscount($loan->discount);

        if ($loan->status_text == 'canceled') {
            $calculator->setIsCanceled(true);
        }

        foreach ($loanItems as $loanItem) {
            $taxRatePercent = ($loanItem->tax_rate_id) ? $loanItem->tax_rate_1_percent : 0;
            $taxRate2Percent = ($loanItem->tax_rate_2_id) ? $loanItem->tax_rate_2_percent : 0;
            $taxRate2IsCompound = ($loanItem->tax_rate_2_is_compound) ? 1 : 0;
            $taxRate1CalculateVat = ($loanItem->tax_rate_1_calculate_vat) ? 1 : 0;

            $calculator->addItem($loanItem->id, $loanItem->price, $loanItem->tenor, $loanItem->interest);
        }

        $calculator->calculate();

        // Get the calculated values
        $calculatedItemAmounts = $calculator->getCalculatedItemAmounts();
        $calculatedAmount = $calculator->getCalculatedAmount();

        // Update the item amount records.
        foreach ($calculatedItemAmounts as $calculatedItemAmount) {
            $loanItemAmount = LoanItemAmount::firstOrNew(['item_id' => $calculatedItemAmount['item_id']]);
            $loanItemAmount->fill($calculatedItemAmount);
            $loanItemAmount->save();
        }

        // Update the overall loan amount record.
        $loanAmount = LoanAmount::firstOrNew(['loan_id' => $loan->id]);
        $loanAmount->fill($calculatedAmount);
        $loanAmount->save();

        // Check to see if the loan should be marked as paid.
        if ($calculatedAmount['total'] > 0 and $calculatedAmount['balance'] <= 0 and $loan->status_text != 'canceled') {
            $loan->loan_status_id = LoanStatuses::getStatusId('paid');
            $loan->save();
        }

        // Check to see if the loan was marked as paid but should no longer be.
        if ($calculatedAmount['total'] > 0 and $calculatedAmount['balance'] > 0 and $loan->loan_status_id == LoanStatuses::getStatusId('paid')) {
            $loan->loan_status_id = LoanStatuses::getStatusId('sent');
            $loan->save();
        }
    }
}
