<?php

namespace FI\Events\Listeners;

use FI\Events\InvestCreating;
use FI\Modules\Currencies\Support\CurrencyConverterFactory;
use FI\Modules\Groups\Models\Group;
use FI\Support\DateFormatter;
use FI\Support\Statuses\InvestStatuses;

class InvestCreatingListener
{
    public function handle(InvestCreating $event)
    {
        $invest = $event->invest;

        if (!$invest->client_id) {
            // This needs to throw an exception since this is required.
        }

        if (!$invest->user_id) {
            $invest->user_id = auth()->user()->id;
        }

        if (!$invest->invest_date) {
            $invest->invest_date = date('Y-m-d');
        }

        if (!$invest->expires_at) {
            $invest->expires_at = DateFormatter::incrementDateByDays($invest->invest_date->format('Y-m-d'), config('fi.investsExpireAfter'));
        }

        if (!$invest->company_profile_id) {
            $invest->company_profile_id = config('fi.defaultCompanyProfile');
        }

        if (!$invest->group_id) {
            $invest->group_id = config('fi.investGroup');
        }

        if (!$invest->number) {
            $invest->number = Group::generateNumber($invest->group_id);
        }

        if (!isset($invest->terms)) {
            $invest->terms = config('fi.investTerms');
        }

        if (!isset($invest->footer)) {
            $invest->footer = config('fi.investFooter');
        }

        if (!$invest->invest_status_id) {
            $invest->invest_status_id = InvestStatuses::getStatusId('draft');
        }

        if (!$invest->currency_code) {
            $invest->currency_code = $invest->client->currency_code;
        }

        if (!$invest->template) {
            $invest->template = $invest->companyProfile->invest_template;
        }

        if ($invest->currency_code == config('fi.baseCurrency')) {
            $invest->exchange_rate = 1;
        } elseif (!$invest->exchange_rate) {
            $currencyConverter = CurrencyConverterFactory::create();
            $invest->exchange_rate = $currencyConverter->convert(config('fi.baseCurrency'), $invest->currency_code);
        }

        $invest->url_key = str_random(32);
    }
}
