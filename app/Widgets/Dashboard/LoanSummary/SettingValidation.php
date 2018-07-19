<?php

return [
    'rules' => [
        'widgetLoanSummaryDashboardTotalsFromDate' => 'required_if:widgetLoanSummaryDashboardTotals,custom_date_range',
        'widgetLoanSummaryDashboardTotalsToDate' => 'required_if:widgetLoanSummaryDashboardTotals,custom_date_range',
    ],
    'messages' => [
        'widgetLoanSummaryDashboardTotalsFromDate.required_if' => trans('fi.validation_loan_summary_from_date'),
        'widgetLoanSummaryDashboardTotalsToDate.required_if' => trans('fi.validation_loan_summary_to_date'),
    ],
];