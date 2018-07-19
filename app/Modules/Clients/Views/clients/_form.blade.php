@include('clients._js_unique_name')

<script type="text/javascript">
  $(function () {
    $('#name').focus();
  });
</script>



        <div class="row">
            <div class="col-md-8" id="col-client-name">
                <div class="form-group">
                    <label>* {{ trans('fi.client_name') }}:</label>
                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                    <!-- <p class="help-block">
                        <small>{{ trans('fi.help_text_client_name') }}
                            <a href="javascript:void(0)" id="btn-show-unique-name"
                               tabindex="-1">{{ trans('fi.view_unique_name') }}</a>
                        </small>
                    </p> -->
                </div>
            </div>
            <div class="col-md-4">
              <img src="" alt="">
            </div>
            <div class="col-md-3" id="col-client-unique-name" style="display: none;">
                <div class="form-group">
                    <label>* {{ trans('fi.unique_name') }}:</label>
                    {!! Form::text('unique_name', null, ['id' => 'unique_name', 'class' => 'form-control']) !!}
                    <p class="help-block">
                        <small>{{ trans('fi.help_text_client_unique_name') }}</small>
                    </p>
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
              <label>{{ trans('fi.passport') }}:</label>
              {{--  @if ($editMode)
                  @include('client_passport._table', ['object' => $client, 'model' => 'FI\Modules\Clients\Models\Client'])

              @endif --}}


            </div>
        </div>

        <div class="form-group">
            <label>{{ trans('fi.address') }}: </label>
            {!! Form::textarea('address', null, ['id' => 'address', 'class' => 'form-control', 'rows' => 4]) !!}
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.city') }}: </label>
                    {!! Form::text('city', null, ['id' => 'city', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.state') }}: </label>
                    {!! Form::text('state', null, ['id' => 'state', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.country') }}: </label>
                    {!! Form::text('country', null, ['id' => 'country', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.date_of_birth') }}: </label>
                    {!! Form::date('dob', null, ['id' => 'dob', 'class' => 'form-control ']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.marital_status') }}: </label>
                    {!! Form::select('marital_status', ['single' => trans('fi.single'), 'married' => trans('fi.married'), 'divorced' => trans('fi.divorced')], ((isset($editMode) and $editMode) ? null : 1), ['id' => 'marital_status', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.gender') }}: </label>
                    {!! Form::select('gender', ['female' => trans('fi.female'), 'male' => trans('fi.male')], ((isset($editMode) and $editMode) ? null : 1), ['id' => 'gender', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ trans('fi.no_children') }}: </label>
                    {!! Form::text('children', null, ['id' => 'children', 'class' => 'form-control']) !!}
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
                  <label>{{ trans('fi.phone_number') }}: </label>
                  {!! Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control']) !!}
              </div>
          </div>

          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.phone_number2') }}: </label>
                  {!! Form::text('phone2', null, ['id' => 'phone2', 'class' => 'form-control']) !!}
              </div>
          </div>

          <div class="col-md-4" id="col-client-email">
              <div class="form-group">
                  <label>{{ trans('fi.email_address') }}: </label>
                  {!! Form::text('client_email', null, ['id' => 'client_email', 'class' => 'form-control']) !!}
              </div>
          </div>


        </div>

        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.next_of_kin') }}: </label>
                  {!! Form::text('nok', null, ['id' => 'nok', 'class' => 'form-control']) !!}
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.phone_of_next_of_kin') }}: </label>
                  {!! Form::text('phone_nok', null, ['id' => 'phone_nok', 'class' => 'form-control']) !!}
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label>{{ trans('fi.relationship_nok') }}: </label>
                  {!! Form::text('relationship_nok', null, ['id' => 'relationship_nok', 'class' => 'form-control']) !!}
              </div>
          </div>
          <div class="col-md-12">
              <div class="form-group">
                  <label>{{ trans('fi.address_nok') }}: </label>
                  {!! Form::textarea('address_nok', null, ['id' => 'address_nok', 'class' => 'form-control', 'rows' => 4]) !!}
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




@if ($customFields->count())
    @include('custom_fields._custom_fields')
@endif
