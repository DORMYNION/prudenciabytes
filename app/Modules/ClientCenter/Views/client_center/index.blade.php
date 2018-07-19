@extends('client_center.layouts.logged_in')

@section('content')

    <section class="content-header">
        <h1>{{ trans('fi.dashboard') }}</h1>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('fi.recent_invests') }}</h3>
                    </div>
                    @if (count($invests))
                        <div class="box-body no-padding">
                            @include('client_center.invests._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.invests') }}"
                                                              class="btn btn-default">{{ trans('fi.view_all') }}</a></p>
                        </div>
                    @else
                        <div class="box-body">
                            <p>{{ trans('fi.no_records_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('fi.recent_loans') }}</h3>
                    </div>
                    @if (count($loans))
                        <div class="box-body no-padding">
                            @include('client_center.loans._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.loans') }}"
                                                              class="btn btn-default">{{ trans('fi.view_all') }}</a></p>
                        </div>
                    @else
                        <div class="box-body">
                            <p>{{ trans('fi.no_records_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('fi.recent_payments') }}</h3>
                    </div>
                    @if (count($payments))
                        <div class="box-body no-padding">
                            @include('client_center.payments._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.payments') }}"
                                                              class="btn btn-default">{{ trans('fi.view_all') }}</a></p>
                        </div>
                    @else
                        <div class="box-body">
                            <p>{{ trans('fi.no_records_found') }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@stop