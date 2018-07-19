@include('layouts._datepicker')

<div id="invest-dashboard-totals-widget">
    <script type="text/javascript">
      $(function () {
        $('.invest-dashboard-total-change-option').click(function () {
          var option = $(this).data('id');

          $.post("{{ route('widgets.dashboard.investSummary.renderPartial') }}", {
            widgetInvestSummaryDashboardTotals: option,
            widgetInvestSummaryDashboardTotalsFromDate: $('#invest-dashboard-total-setting-from-date').val(),
            widgetInvestSummaryDashboardTotalsToDate: $('#invest-dashboard-total-setting-to-date').val()
          }, function (data) {
            $('#invest-dashboard-totals-widget').html(data);
          });

        });

        $('#invest-dashboard-total-setting-from-date').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true
        });
        $('#invest-dashboard-total-setting-to-date').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true
        });
      });
    </script>

    <section class="content">

        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">{{ trans('fi.invest_summary') }}</h3>
                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-calendar"></i> {{ $investDashboardTotalOptions[config('fi.widgetInvestSummaryDashboardTotals')] }}
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @foreach ($investDashboardTotalOptions as $key => $option)
                                <li>
                                    @if ($key != 'custom_date_range')
                                        <a href="#" onclick="return false;" class="invest-dashboard-total-change-option"
                                           data-id="{{ $key }}">{{ $option }}</a>
                                    @else
                                        <a href="#" onclick="return false;" data-toggle="modal"
                                           data-target="#invest-summary-widget-modal">{{ $option }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn btn-box-tool create-invest"><i
                                class="fa fa-plus"></i> {{ trans('fi.create_invest') }}</button>
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3>{{ $investsTotalDraft }}</h3>

                                <p>{{ trans('fi.draft_invests') }}</p>
                            </div>
                            <div class="icon"><i class="ion ion-edit"></i></div>
                            <a class="small-box-footer" href="{{ route('invests.index') }}?status=draft">
                                {{ trans('fi.view_draft_invests') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-olive">
                            <div class="inner">
                                <h3>{{ $investsTotalSent }}</h3>

                                <p>{{ trans('fi.sent_invests') }}</p>
                            </div>
                            <div class="icon"><i class="ion ion-share"></i></div>
                            <a class="small-box-footer" href="{{ route('invests.index') }}?status=sent">
                                {{ trans('fi.view_sent_invests') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{ $investsTotalRejected }}</h3>

                                <p>{{ trans('fi.rejected_invests') }}</p>
                            </div>
                            <div class="icon"><i class="ion ion-thumbsdown"></i></div>
                            <a class="small-box-footer" href="{{ route('invests.index') }}?status=rejected">
                                {{ trans('fi.view_rejected_invests') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3>{{ $investsTotalApproved }}</h3>

                                <p>{{ trans('fi.approved_invests') }}</p>
                            </div>
                            <div class="icon"><i class="ion ion-thumbsup"></i></div>
                            <a class="small-box-footer" href="{{ route('invests.index') }}?status=approved">
                                {{ trans('fi.view_approved_invests') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade" id="invest-summary-widget-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('fi.custom_date_range') }}</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>{{ trans('fi.from_date') }} (yyyy-mm-dd):</label>
                        {!! Form::text('setting_widgetInvestSummaryDashboardTotalsFromDate', config('fi.widgetInvestSummaryDashboardTotalsFromDate'), ['class' => 'form-control', 'id' => 'invest-dashboard-total-setting-from-date']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.to_date') }} (yyyy-mm-dd):</label>
                        {!! Form::text('setting_widgetInvestSummaryDashboardTotalsToDate', config('fi.widgetInvestSummaryDashboardTotalsToDate'), ['class' => 'form-control', 'id' => 'invest-dashboard-total-setting-to-date']) !!}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('fi.cancel') }}</button>
                    <button type="button" class="btn btn-primary invest-dashboard-total-change-option"
                            data-id="custom_date_range" data-dismiss="modal">{{ trans('fi.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>