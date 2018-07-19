<?php

namespace FI\Events\Listeners;

use FI\Events\RecurringLoanCreating;
use FI\Modules\Currencies\Support\CurrencyConverterFactory;

class RecurringLoanCreatingListener
{
    public function handle(RecurringLoanCreating $event)
    {
        $recurringLoan = $event->recurringLoan;

        if (!$recurringLoan->user_id) {
            $recurringLoan->user_id = auth()->user()->id;
        }

        if (!$recurringLoan->company_profile_id) {
            $recurringLoan->company_profile_id = config('fi.defaultCompanyProfile');
        }

        if (!$recurringLoan->group_id) {
            $recurringLoan->group_id = config('fi.loanGroup');
        }

        if (!isset($recurringLoan->terms)) {
            $recurringLoan->terms = config('fi.loanTerms');
        }

        if (!isset($recurringLoan->footer)) {
            $recurringLoan->footer = config('fi.loanFooter');
        }

        if (!$recurringLoan->template) {
            $recurringLoan->template = $recurringLoan->companyProfile->loan_template;
        }

        if (!$recurringLoan->currency_code) {
            $recurringLoan->currency_code = $recurringLoan->client->currency_code;
        }

        if ($recurringLoan->currency_code == config('fi.baseCurrency')) {
            $recurringLoan->exchange_rate = 1;
        } elseif (!$recurringLoan->exchange_rate) {
            $currencyConverter = CurrencyConverterFactory::create();
            $recurringLoan->exchange_rate = $currencyConverter->convert(config('fi.baseCurrency'), $recurringLoan->currency_code);
        }
    }
}
