@extends('layouts.master')

@section('content')


    <section class="content-header">
        <h1 class="pull-left">{{ trans('fi.client_form') }}</h1>

        <div class="pull-right">
            <button class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('fi.save') }}</button>
        </div>

        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

        <div class="row">

            <div class="col-md-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">{{ trans('fi.general') }}</a></li>

                            <li><a href="#tab-contacts" data-toggle="tab">{{ trans('fi.contacts') }}</a></li>
                            <li><a href="#tab-attachments" data-toggle="tab">{{ trans('fi.attachments') }}</a></li>
                            <li><a href="#tab-notes" data-toggle="tab">{{ trans('fi.notes') }}</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            @include('loans._form')
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </section>

    {!! Form::close() !!}

@stop
