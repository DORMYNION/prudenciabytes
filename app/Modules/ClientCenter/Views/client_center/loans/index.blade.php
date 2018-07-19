@extends('client_center.layouts.logged_in')

@section('content')

    <section class="content-header">
        <h1>{{ trans('fi.loans') }}</h1>
    </section>

    <section class="content">

        @include('layouts._alerts')

        <div class="row">

            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-body no-padding">
                        @include('client_center.loans._table')
                    </div>

                </div>

                <div class="pull-right">
                    {!! $loans->render() !!}
                </div>

            </div>

        </div>

    </section>

@stop