@include('invests._js_edit_from')

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('fi.from') }}</h3>

        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" id="btn-change-company_profile">
                <i class="fa fa-exchange"></i> {{ trans('fi.change') }}
            </button>
        </div>
    </div>
    <div class="box-body">
        <strong>{{ $invest->companyProfile->company }}</strong><br>
        {!! $invest->companyProfile->formatted_address !!}<br>
        {{ trans('fi.phone') }}: {{ $invest->companyProfile->phone }}<br>
        {{ trans('fi.email') }}: {{ $invest->user->email }}
    </div>
</div>