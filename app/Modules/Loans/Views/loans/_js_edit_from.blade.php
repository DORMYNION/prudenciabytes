<script type="text/javascript">
  $(function () {
    $('#btn-change-company-profile').click(function () {
      $('#modal-placeholder').load('{{ route('companyProfiles.ajax.modalLookup') }}', {
        id: {{ $loan->id }},
        update_company_profile_route: '{{ route('loanEdit.updateCompanyProfile') }}',
        refresh_from_route: '{{ route('loanEdit.refreshFrom') }}'
      });
    });
  });
</script>