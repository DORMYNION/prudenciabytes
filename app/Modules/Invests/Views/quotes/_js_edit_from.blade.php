<script type="text/javascript">
  $(function () {
    $('#btn-change-company_profile').click(function () {
      $('#modal-placeholder').load('{{ route('companyProfiles.ajax.modalLookup') }}', {
        id: {{ $invest->id }},
        update_company_profile_route: '{{ route('investEdit.updateCompanyProfile') }}',
        refresh_from_route: '{{ route('investEdit.refreshFrom') }}'
      });
    });
  });
</script>