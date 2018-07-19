<script type="text/javascript">

  $(function () {

    $('#create-invest').modal();

    $('#create-invest').on('shown.bs.modal', function () {
      $('#create_client_name').focus();
      $('#create_client_name').typeahead('val', clientName);
    });

    $('#create_invest_date').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

    $('#invest-create-confirm').click(function () {

      $.post('{{ route('invests.store') }}', {
        user_id: $('#user_id').val(),
        company_profile_id: $('#company_profile_id').val(),
        client_name: $('#create_client_name').val(),
        invest_date: $('#create_invest_date').val(),
        group_id: $('#create_group_id').val()
      }).done(function (response) {
        window.location = '{{ url('invests') }}' + '/' + response.id + '/edit';
      }).fail(function (response) {
        showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
      });
    });

  });

</script>