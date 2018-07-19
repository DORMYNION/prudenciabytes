<script type="text/javascript">
  $(function () {
    $('#btn-change-company-profile').click(function () {
      $('#modal-placeholder').load('{{ route('companyProfiles.ajax.modalLookup') }}', {
        id: {{ $recurringLoan->id }},
        update_company_profile_route: '{{ route('recurringLoanEdit.updateCompanyProfile') }}',
        refresh_from_route: '{{ route('recurringLoanEdit.refreshFrom') }}'
      });
    });
  });
</script>