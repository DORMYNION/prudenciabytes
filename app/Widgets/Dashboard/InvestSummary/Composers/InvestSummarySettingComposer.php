<?php

namespace FI\Widgets\Dashboard\InvestSummary\Composers;

class InvestSummarySettingComposer
{
    public function compose($view)
    {
        $view->with('dashboardTotalOptions', periods());
    }
}