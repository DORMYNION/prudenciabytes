<script type="text/javascript">

  $(function () {
    $('#modal-copy-invest').modal();

    $('#modal-copy-invest').on('shown.bs.modal', function () {
      $('#client_name').focus();
    });

    $('#copy_invest_date').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

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

    // Creates the invest
    $('#btn-copy-invest-submit').click(function () {
      $.post('{{ route('investCopy.store') }}', {
        invest_id: {{ $invest->id }},
        client_name: $('#copy_client_name').val(),
        company_profile_id: $('#copy_company_profile_id').val(),
        invest_date: $('#copy_invest_date').val(),
        group_id: $('#copy_group_id').val(),
        user_id: {{ $user_id }}
      }).done(function (response) {
        window.location = '{{ url('invests') }}' + '/' + response.id + '/edit';
      }).fail(function (response) {
        showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
      });
    });
  });

</script>