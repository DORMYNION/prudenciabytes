<?php

namespace FI\Widgets\Dashboard\InvestSummary\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Settings\Models\Setting;

class WidgetController extends Controller
{
    public function renderPartial()
    {
        Setting::saveByKey('widgetInvestSummaryDashboardTotals', request('widgetInvestSummaryDashboardTotals'));

        if (request()->has('widgetInvestSummaryDashboardTotalsFromDate')) {
            Setting::saveByKey('widgetInvestSummaryDashboardTotalsFromDate', request('widgetInvestSummaryDashboardTotalsFromDate'));
        }

        if (request()->has('widgetInvestSummaryDashboardTotalsToDate')) {
            Setting::saveByKey('widgetInvestSummaryDashboardTotalsToDate', request('widgetInvestSummaryDashboardTotalsToDate'));
        }

        Setting::setAll();

        return view('InvestSummaryWidget');
    }
}