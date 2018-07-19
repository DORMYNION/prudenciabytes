@include('layouts._datepicker')

<script type="text/javascript">
  $(function () {
    $('#invest-dashboard-total-setting-from-date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });
    $('#invest-dashboard-total-setting-to-date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('#invest-dashboard-total-setting').change(function () {
      toggleWidgetInvestDashboardTotalsDateRange($('#invest-dashboard-total-setting').val());
    });

    function toggleWidgetInvestDashboardTotalsDateRange (val) {
      if (val == 'custom_date_range') {
        $('#div-invest-dashboard-totals-date-range').show();
      }
      else {
        $('#div-invest-dashboard-totals-date-range').hide();
      }
    }

    toggleWidgetInvestDashboardTotalsDateRange($('#invest-dashboard-total-setting').val());
  });
</script>

<div class="form-group">
    <label>{{ trans('fi.dashboard_totals_option') }}: </label>
    {!! Form::select('setting[widgetInvestSummaryDashboardTotals]', $dashboardTotalOptions, config('fi.widgetInvestSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'invest-dashboard-total-setting']) !!}
</div>

<div class="row" id="div-invest-dashboard-totals-date-range">
    <div class="col-md-2">
        <label>{{ trans('fi.from_date') }} (yyyy-mm-dd):</label>
        {!! Form::text('setting[widgetInvestSummaryDashboardTotalsFromDate]', config('fi.widgetInvestSummaryDashboardTotalsFromDate'), ['class' => 'form-control', 'id' => 'invest-dashboard-total-setting-from-date']) !!}
    </div>
    <div class="col-md-2">
        <label>{{ trans('fi.to_date') }} (yyyy-mm-dd):</label>
        {!! Form::text('setting[widgetInvestSummaryDashboardTotalsToDate]', config('fi.widgetInvestSummaryDashboardTotalsToDate'), ['class' => 'form-control', 'id' => 'invest-dashboard-total-setting-to-date']) !!}
    </div>
</div>