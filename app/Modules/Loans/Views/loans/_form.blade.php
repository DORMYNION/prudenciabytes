@include('clients._js_unique_name')

<script type="text/javascript">
  $(function () {
    $('#name').focus();
  });
</script>



        <div class="row">
            <div class="col-md-12" id="col-client-name">
                <div class="form-group">
                    <label>* {{ trans('fi.client_name') }}:</label>
                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4" id="col-client-active">
                <div class="form-group">
                    <label>{{ trans('fi.active') }}:</label>
                    {!! Form::select('active', ['0' => trans('fi.no'), '1' => trans('fi.yes')], ((isset($editMode) and $editMode) ? null : 1), ['id' => 'active', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.loan_type') }}:</label>
                  {!! Form::select('loan_type', ['new_loan' => trans('fi.new_loan'), 'top_up_loan' => trans('fi.top_up_loan'), 'asset_loan' => trans('fi.asset_loan')], ((isset($editMode) and $editMode) ? null : 1), ['id' => 'loan_type', 'class' => 'form-control']) !!}
              </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.monthly_income') }}: </label>
                    {!! Form::text('monthly_income', null, ['id' => 'monthly_income', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.bank_name') }}: </label>
                  {!! Form::text('bank_name', null, ['id' => 'bank_name', 'class' => 'form-control']) !!}
              </div>
          </div>

          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.acc_no') }}: </label>
                  {!! Form::text('acc_no', null, ['id' => 'acc_no', 'class' => 'form-control']) !!}
              </div>
          </div>

          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.acc_name') }}: </label>
                  {!! Form::text('acc_name', null, ['id' => 'acc_name', 'class' => 'form-control']) !!}
              </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>{{ trans('fi.loan_purpose')}}</label>
              {!! Form::text('loan_purpose', null, ['id' => 'loan_purpose', 'class' => 'form-control']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>{{ trans('fi.requested_amount')}}</label>
              {!! Form::text('requested_amount', null, ['id' => 'requested_amount', 'class' => 'form-control']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>{{ trans('fi.loan_tenor')}}</label>
              {!! Form::text('loan_tenor', null, ['id' => 'loan_tenor', 'class' => 'form-control']) !!}
            </div>
          </div>
        </div>

        <div class="row">
            <div class="col-md-6" id="">
                <div class="form-group">
                    <label>{{ trans('fi.guarantor_name') }}:</label>
                    {!! Form::text('guarantor_name', null, ['id' => 'guarantor_name', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-6" id="">
              <div class="form-group">
                <label>{{ trans('fi.witness_name') }}:</label>
                {!! Form::text('witness_name', null, ['id' => 'witness_name', 'class' => 'form-control']) !!}
              </div>
            </div>


            <div class="col-md-6" id="">
                <div class="form-group">
                    <label>{{ trans('fi.guarantor_contact') }}:</label>
                    {!! Form::text('guarantor_contact', null, ['id' => 'guarantor_contact', 'class' => 'form-control']) !!}
                </div>
            </div>


            <div class="col-md-6" id="">
                <div class="form-group">
                    <label>{{ trans('fi.witness_contact') }}:</label>
                    {!! Form::text('witness_contact', null, ['id' => 'witness_contact', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <p class="text-muted">Please be assured that confirmation given by you will be treated in strict confidence and without prejudice to your organization.</p>
        <p>I confirm that the company is authorised to obtain verification of the information provided by me in ________________________ relation to the loan obtained under the scheme, and i hereby authorize that my salary should be deducted at source with the monlthly loan repayment over the duration of the facility</p>
        {!! Form::checkbox('i_agree', null, ['id' => 'i_agree', 'class' => 'form-control']) !!}
        <label for="i_agree">{{ trans('fi.i_agree') }}:</label>

        <div class="row">
            <div class="col-md-12" id="">
                <div class="form-group">
                    <label>{{ trans('fi.official_comment') }}:</label>
                    {!! Form::textarea('official_comment', null, ['id' => 'official_comment', 'class' => 'form-control', 'rows' => 2]) !!}
                </div>
            </div>
        </div>
