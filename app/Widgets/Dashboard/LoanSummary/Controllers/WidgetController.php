<?php

namespace FI\Widgets\Dashboard\LoanSummary\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Settings\Models\Setting;

class WidgetController extends Controller
{
    public function renderPartial()
    {
        Setting::saveByKey('widgetLoanSummaryDashboardTotals', request('widgetLoanSummaryDashboardTotals'));

        if (request()->has('widgetLoanSummaryDashboardTotalsFromDate')) {
            Setting::saveByKey('widgetLoanSummaryDashboardTotalsFromDate', request('widgetLoanSummaryDashboardTotalsFromDate'));
        }

        if (request()->has('widgetLoanSummaryDashboardTotalsToDate')) {
            Setting::saveByKey('widgetLoanSummaryDashboardTotalsToDate', request('widgetLoanSummaryDashboardTotalsToDate'));
        }

        Setting::setAll();

        return view('LoanSummaryWidget');
    }
}