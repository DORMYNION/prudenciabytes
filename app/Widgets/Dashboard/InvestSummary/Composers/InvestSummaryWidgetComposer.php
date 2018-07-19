<?php

namespace FI\Widgets\Dashboard\InvestSummary\Composers;

use FI\Modules\Invests\Models\InvestAmount;
use FI\Support\CurrencyFormatter;
use Illuminate\Support\Facades\DB;

class InvestSummaryWidgetComposer
{
    public function compose($view)
    {
        $view->with('investsTotalDraft', $this->getInvestTotalDraft())
            ->with('investsTotalSent', $this->getInvestTotalSent())
            ->with('investsTotalApproved', $this->getInvestTotalApproved())
            ->with('investsTotalRejected', $this->getInvestTotalRejected())
            ->with('investDashboardTotalOptions', periods());
    }

    private function getInvestTotalDraft()
    {
        return CurrencyFormatter::format(InvestAmount::join('invests', 'invests.id', '=', 'invest_amounts.invest_id')
            ->whereHas('invest', function ($q) {
                $q->draft();
                $q->where('loan_id', 0);
                switch (config('fi.widgetInvestSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetInvestSummaryDashboardTotalsFromDate'), config('fi.widgetInvestSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getInvestTotalSent()
    {
        return CurrencyFormatter::format(InvestAmount::join('invests', 'invests.id', '=', 'invest_amounts.invest_id')
            ->whereHas('invest', function ($q) {
                $q->sent();
                $q->where('loan_id', 0);
                switch (config('fi.widgetInvestSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetInvestSummaryDashboardTotalsFromDate'), config('fi.widgetInvestSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getInvestTotalApproved()
    {
        return CurrencyFormatter::format(InvestAmount::join('invests', 'invests.id', '=', 'invest_amounts.invest_id')
            ->whereHas('invest', function ($q) {
                $q->approved();
                $q->where('loan_id', 0);
                switch (config('fi.widgetInvestSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetInvestSummaryDashboardTotalsFromDate'), config('fi.widgetInvestSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getInvestTotalRejected()
    {
        return CurrencyFormatter::format(InvestAmount::join('invests', 'invests.id', '=', 'invest_amounts.invest_id')
            ->whereHas('invest', function ($q) {
                $q->rejected();
                $q->where('loan_id', 0);
                switch (config('fi.widgetInvestSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetInvestSummaryDashboardTotalsFromDate'), config('fi.widgetInvestSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }
}