<script type="text/javascript">
  $(function () {
    $('#btn-edit-client').click(function () {
      $('#modal-placeholder').load('{{ route('clients.ajax.modalEdit') }}', {
        client_id: $(this).data('client-id'),
        refresh_to_route: '{{ route('recurringLoanEdit.refreshTo') }}',
        id: {{ $recurringLoan->id }}
      });
    });

    $('#btn-change-client').click(function () {
      $('#modal-placeholder').load('{{ route('clients.ajax.modalLookup') }}', {
        id: {{ $recurringLoan->id }},
        update_client_id_route: '{{ route('recurringLoanEdit.updateClient') }}',
        refresh_to_route: '{{ route('recurringLoanEdit.refreshTo') }}'
      });
    });
  });
</script>