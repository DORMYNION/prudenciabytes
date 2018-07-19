<script type="text/javascript">
  $(function () {
    $('#btn-edit-client').click(function () {
      $('#modal-placeholder').load('{{ route('clients.ajax.modalEdit') }}', {
        client_id: $(this).data('client-id'),
        refresh_to_route: '{{ route('loanEdit.refreshTo') }}',
        id: {{ $loan->id }}
      });
    });

    $('#btn-change-client').click(function () {
      $('#modal-placeholder').load('{{ route('clients.ajax.modalLookup') }}', {
        id: {{ $loan->id }},
        update_client_id_route: '{{ route('loanEdit.updateClient') }}',
        refresh_to_route: '{{ route('loanEdit.refreshTo') }}'
      });
    });
  });
</script>