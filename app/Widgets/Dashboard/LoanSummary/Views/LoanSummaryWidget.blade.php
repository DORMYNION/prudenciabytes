@include('layouts._datepicker')

<div id="loan-dashboard-totals-widget">
    <script type="text/javascript">
      $(function () {
        $('.loan-dashboard-total-change-option').click(function () {
          var option = $(this).data('id');

          $.post("{{ route('widgets.dashboard.loanSummary.renderPartial') }}", {
            widgetLoanSummaryDashboardTotals: option,
            widgetLoanSummaryDashboardTotalsFromDate: $('#loan-dashboard-total-setting-from-date').val(),
            widgetLoanSummaryDashboardTotalsToDate: $('#loan-dashboard-total-setting-to-date').val()
          }, function (data) {
            $('#loan-dashboard-totals-widget').html(data);
          });

        });

        $('#loan-dashboard-total-setting-from-date').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true
        });
        $('#loan-dashboard-total-setting-to-date').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true
        });
      });
    </script>

    <section class="content">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">{{ trans('fi.loan_summary') }}</h3>

                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-calendar"></i> {{ $loanDashboardTotalOptions[config('fi.widgetLoanSummaryDashboardTotals')] }}
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @foreach ($loanDashboardTotalOptions as $key => $option)
                                <li>
                                    @if ($key != 'custom_date_range')
                                        <a href="#" onclick="return false;"
                                           class="loan-dashboard-total-change-option"
                                           data-id="{{ $key }}">{{ $option }}</a>
                                    @else
                                        <a href="#" onclick="return false;" data-toggle="modal"
                                           data-target="#loan-summary-widget-modal">{{ $option }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn btn-box-tool create-loan"><i
                                class="fa fa-plus"></i> {{ trans('fi.create_loan') }}</button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{ $loansTotalDraft }}</h3>

                                <p>{{ trans('fi.draft_loans') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-edit"></i>
                            </div>
                            <a href="{{ route('loans.index') }}?status=draft" class="small-box-footer">
                                {{ trans('fi.view_draft_loans') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ $loansTotalSent }}</h3>

                                <p>{{ trans('fi.sent_loans') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-share"></i>
                            </div>
                            <a class="small-box-footer" href="{{ route('loans.index') }}?status=sent">
                                {{ trans('fi.view_sent_loans') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{ $loansTotalOverdue }}</h3>

                                <p>{{ trans('fi.overdue_loans') }}</p>
                            </div>
                            <div class="icon"><i class="ion ion-alert"></i></div>
                            <a class="small-box-footer" href="{{ route('loans.index') }}?status=overdue">
                                {{ trans('fi.view_overdue_loans') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{ $loansTotalPaid }}</h3>

                                <p>{{ trans('fi.payments_collected') }}</p>
                            </div>
                            <div class="icon"><i class="ion ion-heart"></i></div>
                            <a class="small-box-footer" href="{{ route('payments.index') }}">
                                {{ trans('fi.view_payments') }} <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="loan-summary-widget-modal" tabindex="-1" role="dialog">
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
                        {!! Form::text('setting_widgetLoanSummaryDashboardTotalsFromDate', config('fi.widgetLoanSummaryDashboardTotalsFromDate'), ['class' => 'form-control', 'id' => 'loan-dashboard-total-setting-from-date']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.to_date') }} (yyyy-mm-dd):</label>
                        {!! Form::text('setting_widgetLoanSummaryDashboardTotalsToDate', config('fi.widgetLoanSummaryDashboardTotalsToDate'), ['class' => 'form-control', 'id' => 'loan-dashboard-total-setting-to-date']) !!}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('fi.cancel') }}</button>
                    <button type="button" class="btn btn-primary loan-dashboard-total-change-option"
                            data-id="custom_date_range" data-dismiss="modal">{{ trans('fi.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>