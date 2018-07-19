<script type="text/javascript">

  $(function () {
    // Display the create invest modal
    $('#modal-invest-to-loan').modal('show');

    $('#to_loan_date').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

    // Creates the loan
    $('#btn-invest-to-loan-submit').click(function () {
      $.post('{{ route('investToLoan.store') }}', {
        invest_id: {{ $invest_id }},
        client_id: {{ $client_id }},
        loan_date: $('#to_loan_date').val(),
        group_id: $('#to_loan_group_id').val(),
        user_id: {{ $user_id }}



      }).done(function (response) {
        window.location = response.redirectTo;
      }).fail(function (response) {
        if (response.status == 400) {
          showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
        } else {
          alert('{{ trans('fi.unknown_error') }}');
        }
      });
    });
  });

</script>