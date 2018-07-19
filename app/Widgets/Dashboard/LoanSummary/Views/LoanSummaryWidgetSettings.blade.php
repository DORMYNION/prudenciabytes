@include('layouts._datepicker')

<script type="text/javascript">
  $(function () {
    $('#loan-dashboard-total-setting-from-date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });
    $('#loan-dashboard-total-setting-to-date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('#loan-dashboard-total-setting').change(function () {
      toggleWidgetLoanDashboardTotalsDateRange($('#loan-dashboard-total-setting').val());
    });

    function toggleWidgetLoanDashboardTotalsDateRange (val) {
      if (val == 'custom_date_range') {
        $('#div-loan-dashboard-totals-date-range').show();
      }
      else {
        $('#div-loan-dashboard-totals-date-range').hide();
      }
    }

    toggleWidgetLoanDashboardTotalsDateRange($('#loan-dashboard-total-setting').val());
  });
</script>

<div class="form-group">
    <label>{{ trans('fi.dashboard_totals_option') }}: </label>
    {!! Form::select('setting[widgetLoanSummaryDashboardTotals]', $dashboardTotalOptions, config('fi.widgetLoanSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'loan-dashboard-total-setting']) !!}
</div>

<div class="row" id="div-loan-dashboard-totals-date-range">
    <div class="col-md-2">
        <label>{{ trans('fi.from_date') }} (yyyy-mm-dd):</label>
        {!! Form::text('setting[widgetLoanSummaryDashboardTotalsFromDate]', config('fi.widgetLoanSummaryDashboardTotalsFromDate'), ['class' => 'form-control', 'id' => 'loan-dashboard-total-setting-from-date']) !!}
    </div>
    <div class="col-md-2">
        <label>{{ trans('fi.to_date') }} (yyyy-mm-dd):</label>
        {!! Form::text('setting[widgetLoanSummaryDashboardTotalsToDate]', config('fi.widgetLoanSummaryDashboardTotalsToDate'), ['class' => 'form-control', 'id' => 'loan-dashboard-total-setting-to-date']) !!}
    </div>
</div>