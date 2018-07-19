<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.default_loan_template') }}: </label>
            {!! Form::select('setting[loanTemplate]', $loanTemplates, config('fi.loanTemplate'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.default_group') }}: </label>
            {!! Form::select('setting[loanGroup]', $groups, config('fi.loanGroup'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.loans_due_after') }}: </label>
            {!! Form::text('setting[loansDueAfter]', config('fi.loansDueAfter'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.default_status_filter') }}: </label>
            {!! Form::select('setting[loanStatusFilter]', $loanStatuses, config('fi.loanStatusFilter'), ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

<div class="form-group">
    <label>{{ trans('fi.default_terms') }}: </label>
    {!! Form::textarea('setting[loanTerms]', config('fi.loanTerms'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_footer') }}: </label>
    {!! Form::textarea('setting[loanFooter]', config('fi.loanFooter'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.automatic_email_on_recur') }}: </label>
            {!! Form::select('setting[automaticEmailOnRecur]', ['0' => trans('fi.no'), '1' => trans('fi.yes')], config('fi.automaticEmailOnRecur'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.automatic_email_payment_receipts') }}: </label>
            {!! Form::select('setting[automaticEmailPaymentReceipts]', ['0' => trans('fi.no'), '1' => trans('fi.yes')], config('fi.automaticEmailPaymentReceipts'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.online_payment_method') }}: </label>
            {!! Form::select('setting[onlinePaymentMethod]', $paymentMethods, config('fi.onlinePaymentMethod'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.allow_payments_without_balance') }}: </label>
            {!! Form::select('setting[allowPaymentsWithoutBalance]', $yesNoArray, config('fi.allowPaymentsWithoutBalance'), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.if_loan_is_emailed_while_draft') }}: </label>
            {!! Form::select('setting[resetLoanDateEmailDraft]', $loanWhenDraftOptions, config('fi.resetLoanDateEmailDraft'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.recalculate_loans') }}: </label><br>
            <button type="button" class="btn btn-default" id="btn-recalculate-loans"
                    data-loading-text="{{ trans('fi.recalculating_wait') }}">{{ trans('fi.recalculate') }}</button>
            <p class="help-block">{{ trans('fi.recalculate_help_text') }}</p>
        </div>
    </div>
</div>