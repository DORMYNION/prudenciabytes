<script type="text/javascript">

  $(function () {

    $('#create-loan').modal();

    $('#create-loan').on('shown.bs.modal', function () {
      $('#create_client_name').focus();
      $('#create_client_name').typeahead('val', clientName);
    });

    $('#create_loan_date').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

    $('#loan-create-confirm').click(function () {

      $.post('{{ route('loans.store') }}', {
        user_id: $('#user_id').val(),
        company_profile_id: $('#company_profile_id').val(),
        client_name: $('#create_client_name').val(),
        loan_date: $('#create_loan_date').val(),
        group_id: $('#create_group_id').val()
      }).done(function (response) {
        window.location = '{{ url('loans') }}' + '/' + response.id + '/edit';
      }).fail(function (response) {
        showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
      });
    });

  });

</script>
