@include('loans._js_edit')

<section class="content-header">
    <h1 class="pull-left">{{ trans('ln.loan') }} #{{ $loan->number }}</h1>

    @if ($loan->viewed)
        <span style="margin-left: 10px;" class="label label-success">{{ trans('fi.viewed') }}</span>
    @else
        <span style="margin-left: 10px;" class="label label-default">{{ trans('fi.not_viewed') }}</span>
    @endif

    @if ($loan->invest()->count())
        <span class="label label-info"><a href="{{ route('invests.edit', [$loan->invest->id]) }}"
                                          style="color: inherit;">{{ trans('fi.converted_from_invest') }} {{ $loan->invest->number }}</a></span>
    @endif

    <div class="pull-right">

        <a href="{{ route('loans.pdf', [$loan->id]) }}" target="_blank" id="btn-pdf-loan"
           class="btn btn-default"><i class="fa fa-print"></i> {{ trans('fi.pdf') }}</a>
        @if (config('fi.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-loan" class="btn btn-default email-loan"
               data-loan-id="{{ $loan->id }}" data-redirect-to="{{ route('loans.edit', [$loan->id]) }}"><i
                        class="fa fa-envelope"></i> {{ trans('fi.email') }}</a>
        @endif

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {{ trans('fi.other') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                @if ($loan->isPayable or config('fi.allowPaymentsWithoutBalance'))
                    <li><a href="javascript:void(0)" id="btn-enter-payment" class="enter-payment"
                           data-loan-id="{{ $loan->id }}"
                           data-loan-balance="{{ $loan->amount->formatted_numeric_balance }}"
                           data-redirect-to="{{ route('loans.edit', [$loan->id]) }}"><i
                                    class="fa fa-credit-card"></i> {{ trans('fi.enter_payment') }}</a></li>
                @endif
                <li><a href="javascript:void(0)" id="btn-copy-loan"><i
                                class="fa fa-copy"></i> {{ trans('fi.copy') }}</a></li>
                <li><a href="{{ route('clientCenter.public.loan.show', [$loan->url_key]) }}" target="_blank"><i
                                class="fa fa-globe"></i> {{ trans('fi.public') }}</a></li>
                <li class="divider"></li>
                <li><a href="{{ route('loans.delete', [$loan->id]) }}"
                       onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
            </ul>
        </div>

        <div class="btn-group">
            @if ($returnUrl)
                <a href="{{ $returnUrl }}" class="btn btn-default"><i
                            class="fa fa-backward"></i> {{ trans('fi.back') }}</a>
            @endif
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-save-loan"><i class="fa fa-save"></i> {{ trans('fi.save') }}</button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="#" class="btn-save-loan" data-apply-exchange-rate="1">{{ trans('fi.save_and_apply_exchange_rate') }}</a></li>
            </ul>
        </div>

    </div>

    <div class="clearfix"></div>
</section>

<section class="content">

    <div class="row">

        <div class="col-lg-10">

            <div id="form-status-placeholder"></div>

            <div class="row">

                <div class="col-sm-12" id="col-to">

                    @include('loans._edit_to')

                </div>

            </div>

            <div class="row">

                <div class="col-sm-12 table-responsive" style="overflow-x: visible;">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.items') }}</h3>
                        </div>

                        <div class="box-body">
                            <table id="item-table" class="table table-hover">
                                <thead>
                                  <tr>
                                      <th style="width: 33.33%;">{{ trans('fi.amount') }}</th>
                                      <th style="width: 33.33%;">{{ trans('fi.tenor') }}</th>
                                      <th style="width: 33.33%;">{{ trans('fi.interest') }}</th>
                                      <th style="width: 5%;"></th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <tr id="new-item" style="display: none;">
                                        <td>
                                            {!! Form::hidden('loan_id', $loan->id) !!}
                                            {!! Form::hidden('id', '') !!}
                                            {!! Form::text('price', null, ['class' => 'form-control']) !!}
                                            <label><input type="checkbox" name="save_item_as_lookup"
                                                          tabindex="999" hidden> {{-- trans('fi.save_item_as_lookup') --}}</label>
                                        </td>
                                        <td>{!! Form::text('tenor', null, ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('interest', null, ['class' => 'form-control']) !!}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($loan->items as $item)
                                        <tr class="item" id="tr-item-{{ $item->id }}">
                                            <td>
                                                {!! Form::hidden('loan_id', $loan->id) !!}
                                                {!! Form::hidden('id', $item->id) !!}
                                                {!! Form::text('price', $item->formatted_numeric_price, ['class' => 'form-control']) !!}
                                            </td>
                                            <td>{!! Form::text('tenor', $item->tenor, ['class' => 'form-control']) !!}</td>
                                            <td>{!! Form::text('interest', $item->interest, ['class' => 'form-control']) !!}</td>
                                            <td style="text-align: right; padding-right: 25px;">{{ $item->amount->formatted_subtotal }}</td>
                                            <td>
                                                <a class="btn btn-xs btn-default btn-delete-loan-item"
                                                   href="javascript:void(0);"
                                                   title="{{ trans('fi.delete') }}" data-item-id="{{ $item->id }}">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>
                              <table class="table-hover">
                                <thead>
                                  <tr>
                                      <th style="width: 10%;">{{ trans('fi.sn') }}</th>
                                      <th style="width: 30%;">{{ trans('fi.date') }}</th>
                                      <th style="width: 30%;">{{ trans('fi.balance') }}</th>
                                      <th style="width: 30%;">{{ trans('fi.interest-payment') }}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-payments" data-toggle="tab">{{ trans('fi.payments') }}</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane" id="tab-payments active">
                                <table class="table table-hover">

                                    <thead>
                                    <tr>
                                        <th>{{ trans('fi.payment_date') }}</th>
                                        <th>{{ trans('fi.amount') }}</th>
                                        <th>{{ trans('fi.payment_method') }}</th>
                                        <th>{{ trans('fi.note') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($loan->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->formatted_paid_at }}</td>
                                            <td>{{ $payment->formatted_amount }}</td>
                                            <td>@if ($payment->paymentMethod) {{ $payment->paymentMethod->name }} @endif</td>
                                            <td>{{ $payment->note }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2">

            <div id="div-totals">
                @include('loans._edit_totals')
            </div>

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">{{ trans('fi.options') }}</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label>{{ trans('ln.loan') }} #</label>
                        {!! Form::text('number', $loan->number, ['id' => 'number', 'class' =>
                        'form-control
                        input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.date') }}</label>
                        {!! Form::text('loan_date', $loan->formatted_loan_date, ['id' =>
                        'loan_date', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.due_date') }}</label>
                        {!! Form::text('due_at', $loan->formatted_due_at, ['id' => 'due_at', 'class'
                        => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.discount') }}</label>
                        <div class="input-group">
                            {!! Form::text('discount', $loan->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control input-sm']) !!}
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.currency') }}</label>
                        {!! Form::select('currency_code', $currencies, $loan->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.exchange_rate') }}</label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $loan->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control input-sm']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" id="btn-update-exchange-rate" type="button"
                                        data-toggle="tooltip" data-placement="left"
                                        title="{{ trans('fi.update_exchange_rate') }}"><i class="fa fa-refresh"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.status') }}</label>
                        {!! Form::select('loan_status_id', $statuses, $loan->loan_status_id,
                        ['id' => 'loan_status_id', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.template') }}</label>
                        {!! Form::select('template', $templates, $loan->template,
                        ['id' => 'template', 'class' => 'form-control input-sm']) !!}
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>
