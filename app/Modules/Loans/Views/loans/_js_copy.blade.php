<script type="text/javascript">

  $(function () {
    $('#modal-copy-loan').modal();

    $('#modal-copy-loan').on('shown.bs.modal', function () {
      $('#client_name').focus();
    });

    $('#copy_loan_date').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

    var clients = new Bloodhound({
      datumTokenizer: function (d) {
        return Bloodhound.tokenizers.whitespace(d.num);
      },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: '{{ route('clients.ajax.lookup') }}' + '?query=%QUERY'
    });

    clients.initialize();

    $('#copy_client_name').typeahead(null, {
      minLength: 3,
      source: clients.ttAdapter()
    });

    // Creates the loan
    $('#btn-copy-loan-submit').click(function () {
      $.post('{{ route('loanCopy.store') }}', {
        loan_id: {{ $loan->id }},
        client_name: $('#copy_client_name').val(),
        company_profile_id: $('#copy_company_profile_id').val(),
        loan_date: $('#copy_loan_date').val(),
        group_id: $('#copy_group_id').val(),
        user_id: {{ $user_id }}
      }).done(function (response) {
        window.location = '{{ url('loans') }}' + '/' + response.id + '/edit';
      }).fail(function (response) {
        showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
      });
    });
  });

</script>
