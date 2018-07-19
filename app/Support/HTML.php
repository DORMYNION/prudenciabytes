<?php

namespace FI\Support;

use FI\Events\InvestHTMLCreating;
use FI\Events\LoanHTMLCreating;

class HTML
{
    public static function invest($invest)
    {
        app()->setLocale($invest->client->language);

        config(['fi.baseCurrency' => $invest->currency_code]);

        event(new InvestHTMLCreating($invest));

        $template = str_replace('.blade.php', '', $invest->template);

        if (view()->exists('invest_templates.' . $template)) {
            $template = 'invest_templates.' . $template;
        } else {
            $template = 'templates.invests.default';
        }

        return view($template)
            ->with('invest', $invest)
            ->with('logo', $invest->companyProfile->logo())->render();
    }

    public static function loan($loan)
    {
        app()->setLocale($loan->client->language);

        config(['fi.baseCurrency' => $loan->currency_code]);

        event(new LoanHTMLCreating($loan));

        $template = str_replace('.blade.php', '', $loan->template);

        if (view()->exists('loan_templates.' . $template)) {
            $template = 'loan_templates.' . $template;
        } else {
            $template = 'templates.loans.default';
        }

        return view($template)
            ->with('loan', $loan)
            ->with('logo', $loan->companyProfile->logo())->render();
    }
}
