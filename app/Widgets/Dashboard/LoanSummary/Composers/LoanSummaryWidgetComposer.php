<?php

namespace FI\Widgets\Dashboard\LoanSummary\Composers;

use FI\Modules\Loans\Models\LoanAmount;
use FI\Modules\Payments\Models\Payment;
use FI\Support\CurrencyFormatter;
use Illuminate\Support\Facades\DB;

class LoanSummaryWidgetComposer
{
    public function compose($view)
    {
        $view->with('loansTotalDraft', $this->getLoansTotalDraft())
            ->with('loansTotalSent', $this->getLoansTotalSent())
            ->with('loansTotalPaid', $this->getLoansTotalPaid())
            ->with('loansTotalOverdue', $this->getLoansTotalOverdue())
            ->with('loanDashboardTotalOptions', periods());
    }

    private function getLoansTotalDraft()
    {
        return CurrencyFormatter::format(LoanAmount::join('loans', 'loans.id', '=', 'loan_amounts.loan_id')
            ->whereHas('loan', function ($q) {
                $q->draft();
                switch (config('fi.widgetLoanSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetLoanSummaryDashboardTotalsFromDate'), config('fi.widgetLoanSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('balance / exchange_rate')));
    }

    private function getLoansTotalSent()
    {
        return CurrencyFormatter::format(LoanAmount::join('loans', 'loans.id', '=', 'loan_amounts.loan_id')
            ->whereHas('loan', function ($q) {
                $q->sent();
                switch (config('fi.widgetLoanSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetLoanSummaryDashboardTotalsFromDate'), config('fi.widgetLoanSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('balance / exchange_rate')));
    }

    private function getLoansTotalPaid()
    {
        $payments = Payment::join('loans', 'loans.id', '=', 'payments.loan_id');

        switch (config('fi.widgetLoanSummaryDashboardTotals')) {
            case 'year_to_date':
                $payments->yearToDate();
                break;
            case 'this_quarter':
                $payments->thisQuarter();
                break;
            case 'custom_date_range':
                $payments->dateRange(config('fi.widgetLoanSummaryDashboardTotalsFromDate'), config('fi.widgetLoanSummaryDashboardTotalsToDate'));
                break;
        }

        return CurrencyFormatter::format($payments->sum(DB::raw('amount / exchange_rate')));
    }

    private function getLoansTotalOverdue()
    {
        return CurrencyFormatter::format(LoanAmount::join('loans', 'loans.id', '=', 'loan_amounts.loan_id')
            ->whereHas('loan', function ($q) {
                $q->overdue();
                switch (config('fi.widgetLoanSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetLoanSummaryDashboardTotalsFromDate'), config('fi.widgetLoanSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('balance / exchange_rate')));
    }
}