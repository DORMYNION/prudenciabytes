<?php

return [
    'rules' => [
        'widgetInvestSummaryDashboardTotalsFromDate' => 'required_if:widgetInvestSummaryDashboardTotals,custom_date_range',
        'widgetInvestSummaryDashboardTotalsToDate' => 'required_if:widgetInvestSummaryDashboardTotals,custom_date_range',
    ],
    'messages' => [
        'widgetInvestSummaryDashboardTotalsFromDate.required_if' => trans('fi.validation_invest_summary_from_date'),
        'widgetInvestSummaryDashboardTotalsToDate.required_if' => trans('fi.validation_invest_summary_to_date'),
    ],
];