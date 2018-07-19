<?php

namespace FI\Events\Listeners;

use FI\Events\CompanyProfileCreating;

class CompanyProfileCreatingListener
{
    public function __construct()
    {
        //
    }

    public function handle(CompanyProfileCreating $event)
    {
        if (!$event->companyProfile->loan_template) {
            $event->companyProfile->loan_template = config('fi.loanTemplate');
        }

        if (!$event->companyProfile->invest_template) {
            $event->companyProfile->invest_template = config('fi.investTemplate');
        }
    }
}
