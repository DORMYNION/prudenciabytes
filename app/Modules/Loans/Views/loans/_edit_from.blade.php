@include('loans._js_edit_from')

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('fi.from') }}</h3>

        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" id="btn-change-company-profile">
                <i class="fa fa-exchange"></i> {{ trans('fi.change') }}
            </button>
        </div>
    </div>
    <div class="box-body">
        <strong>{{ $loan->companyProfile->company }}</strong><br>
        {!! $loan->companyProfile->formatted_address !!}<br>
        {{ trans('fi.phone') }}: {{ $loan->companyProfile->phone }}<br>
        {{ trans('fi.email') }}: {{ $loan->user->email }}
    </div>
</div>
