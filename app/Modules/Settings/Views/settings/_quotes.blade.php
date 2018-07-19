<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.default_invest_template') }}: </label>
            {!! Form::select('setting[investTemplate]', $investTemplates, config('fi.investTemplate'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.default_group') }}: </label>
            {!! Form::select('setting[investGroup]', $groups, config('fi.investGroup'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.invests_expire_after') }}: </label>
            {!! Form::text('setting[investsExpireAfter]', config('fi.investsExpireAfter'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.default_status_filter') }}: </label>
            {!! Form::select('setting[investStatusFilter]', $investStatuses, config('fi.investStatusFilter'), ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

<div class="form-group">
    <label>{{ trans('fi.convert_invest_when_approved') }}: </label>
    {!! Form::select('setting[convertInvestWhenApproved]', $yesNoArray, config('fi.convertInvestWhenApproved'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.convert_invest_setting') }}: </label>
    {!! Form::select('setting[convertInvestTerms]', $convertInvestOptions, config('fi.convertInvestTerms'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_terms') }}: </label>
    {!! Form::textarea('setting[investTerms]', config('fi.investTerms'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_footer') }}: </label>
    {!! Form::textarea('setting[investFooter]', config('fi.investFooter'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.if_invest_is_emailed_while_draft') }}: </label>
            {!! Form::select('setting[resetInvestDateEmailDraft]', $investWhenDraftOptions, config('fi.resetInvestDateEmailDraft'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.recalculate_invests') }}: </label><br>
            <button type="button" class="btn btn-default" id="btn-recalculate-invests"
                    data-loading-text="{{ trans('fi.recalculating_wait') }}">{{ trans('fi.recalculate') }}</button>
            <p class="help-block">{{ trans('fi.recalculate_help_text') }}</p>
        </div>
    </div>
</div>