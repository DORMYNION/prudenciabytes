<?php

namespace FI\Events\Listeners;

use FI\Events\LoanCreating;
use FI\Modules\Currencies\Support\CurrencyConverterFactory;
use FI\Modules\Groups\Models\Group;
use FI\Support\DateFormatter;
use FI\Support\Statuses\LoanStatuses;

class LoanCreatingListener
{
    public function handle(LoanCreating $event)
    {
        $loan = $event->loan;

        if (!$loan->client_id) {
            // This needs to throw an exception since this is required.
        }

        if (!$loan->user_id) {
            $loan->user_id = auth()->user()->id;
        }

        if (!$loan->loan_date) {
            $loan->loan_date = date('Y-m-d');
        }

        if (!$loan->due_at) {
            $loan->due_at = DateFormatter::incrementDateByDays($loan->loan_date->format('Y-m-d'), config('fi.loansDueAfter'));
        }

        if (!$loan->company_profile_id) {
            $loan->company_profile_id = config('fi.defaultCompanyProfile');
        }

        if (!$loan->group_id) {
            $loan->group_id = config('fi.loanGroup');
        }

        if (!$loan->number) {
            $loan->number = Group::generateNumber($loan->group_id);
        }

        if (!isset($loan->terms)) {
            $loan->terms = config('fi.loanTerms');
        }

        if (!isset($loan->footer)) {
            $loan->footer = config('fi.loanFooter');
        }

        if (!$loan->loan_status_id) {
            $loan->loan_status_id = LoanStatuses::getStatusId('draft');
        }

        if (!$loan->currency_code) {
            $loan->currency_code = $loan->client->currency_code;
        }

        if (!$loan->template) {
            $loan->template = $loan->companyProfile->loan_template;
        }

        if ($loan->currency_code == config('fi.baseCurrency')) {
            $loan->exchange_rate = 1;
        } elseif (!$loan->exchange_rate) {
            $currencyConverter = CurrencyConverterFactory::create();
            $loan->exchange_rate = $currencyConverter->convert(config('fi.baseCurrency'), $loan->currency_code);
        }

        $loan->url_key = str_random(32);
    }
}
