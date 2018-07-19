@extends('layouts.master')

@section('javascript')

    @include('layouts._datepicker')
    @include('layouts._typeahead')
    @include('item_lookups._js_item_lookups')

@stop

@section('content')

    <div id="div-recurring-loan-edit">

        @include('recurring_loans._edit')

    </div>

@stop