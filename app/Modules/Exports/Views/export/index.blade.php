@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1>{{ trans('fi.export_data') }}</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" id="setting-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-clients">{{ trans('fi.clients') }}</a></li>
                        <li><a data-toggle="tab" href="#tab-invests">{{ trans('fi.invests') }}</a></li>
                        <li><a data-toggle="tab" href="#tab-invest-items">{{ trans('fi.invest_items') }}</a></li>
                        <li><a data-toggle="tab" href="#tab-loans">{{ trans('fi.loans') }}</a></li>
                        <li><a data-toggle="tab" href="#tab-loan-items">{{ trans('fi.loan_items') }}</a></li>
                        <li><a data-toggle="tab" href="#tab-payments">{{ trans('fi.payments') }}</a></li>
                        <li><a data-toggle="tab" href="#tab-expenses">{{ trans('fi.expenses') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-clients" class="tab-pane active">
                            {!! Form::open(['route' => ['export.export', 'Clients'], 'id' => 'client-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_clients') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="tab-invests" class="tab-pane">
                            {!! Form::open(['route' => ['export.export', 'Invests'], 'id' => 'invest-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_invests') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="tab-invest-items" class="tab-pane">
                            {!! Form::open(['route' => ['export.export', 'InvestItems'], 'id' => 'invest-item-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_invest_items') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="tab-loans" class="tab-pane">
                            {!! Form::open(['route' => ['export.export', 'Loans'], 'id' => 'loan-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_loans') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="tab-loan-items" class="tab-pane">
                            {!! Form::open(['route' => ['export.export', 'LoanItems'], 'id' => 'loan-item-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_loan_items') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="tab-payments" class="tab-pane">
                            {!! Form::open(['route' => ['export.export', 'Payments'], 'id' => 'payment-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_payments') }}</button>
                            {!! Form::close() !!}
                        </div>
                        <div id="tab-expenses" class="tab-pane">
                            {!! Form::open(['route' => ['export.export', 'Expenses'], 'id' => 'export-export-form', 'target' => '_blank']) !!}
                            <div class="form-group">
                                <label>{{ trans('fi.format') }}:</label>
                                {!! Form::select('writer', $writers, null, ['class' => 'form-control']) !!}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> {{ trans('fi.export_expenses') }}</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop