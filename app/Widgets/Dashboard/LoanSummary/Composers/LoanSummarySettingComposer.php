<?php

namespace FI\Widgets\Dashboard\LoanSummary\Composers;

class LoanSummarySettingComposer
{
    public function compose($view)
    {
        $view->with('dashboardTotalOptions', periods());
    }
}