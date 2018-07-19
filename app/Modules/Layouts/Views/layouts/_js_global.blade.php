<script type="text/javascript">

  function notify (message, type) {
    $.notify({
      message: message
    }, {
      type: type
    });
  }

  function showErrors (errors, placeholder) {

    $('.input-group.has-error').removeClass('has-error');
    $(placeholder).html('');
    if (errors == null && placeholder) {
      return;
    }

    $.each(errors, function (id, message) {
      if (id) $('#' + id).parents('.input-group').addClass('has-error');
      if (placeholder) $(placeholder).append('<div class="alert alert-danger">' + message[0] + '</div>');
    });

  }

  function clearErrors () {
    $('.input-group.has-error').removeClass('has-error');
  }

  $(function () {

    $.notifyDefaults({
      placement: {
        from: 'bottom'
      }
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.create-invest').click(function () {
      clientName = $(this).data('unique-name');
      $('#modal-placeholder').load('{{ route('invests.create') }}');
    });

    $('.create-loan').click(function () {
      clientName = $(this).data('unique-name');
      $('#modal-placeholder').load('{{ route('loans.create') }}');
    });

    $('.create-loan').click(function () {
      clientName = $(this).data('unique-name');
      $('#modal-placeholder').load('{{ route('loans.create') }}');
    });

    $('.create-recurring-loan').click(function () {
      clientName = $(this).data('unique-name');
      $('#modal-placeholder').load('{{ route('recurringLoans.create') }}');
    });

    $(document).on('click', '.email-invest', function () {
      $('#modal-placeholder').load('{{ route('investMail.create') }}', {
        invest_id: $(this).data('invest-id'),
        redirectTo: $(this).data('redirect-to')
      }, function (response, status, xhr) {
        if (status == 'error') {
          alert('{{ trans('fi.problem_with_email_template') }}');
        }
      });
    });

    $(document).on('click', '.email-loan', function () {
      $('#modal-placeholder').load('{{ route('loanMail.create') }}', {
        loan_id: $(this).data('loan-id'),
        redirectTo: $(this).data('redirect-to')
      }, function (response, status, xhr) {
        if (status == 'error') {
          alert('{{ trans('fi.problem_with_email_template') }}');
        }
      });
    });

    $(document).on('click', '.enter-payment', function () {
      $('#modal-placeholder').load('{{ route('payments.create') }}', {
        loan_id: $(this).data('loan-id'),
        loan_balance: $(this).data('loan-balance'),
        redirectTo: $(this).data('redirect-to')
      });
    });

    $('#bulk-select-all').click(function () {
      if ($(this).prop('checked')) {
        $('.bulk-record').prop('checked', true);
        if ($('.bulk-record:checked').length > 0) {
          $('.bulk-actions').show();
        }
      }
      else {
        $('.bulk-record').prop('checked', false);
        $('.bulk-actions').hide();
      }
    });

    $('.bulk-record').click(function () {
      if ($('.bulk-record:checked').length > 0) {
        $('.bulk-actions').show();
      }
      else {
        $('.bulk-actions').hide();
      }
    });

    $('.bulk-actions').hide();

  });

  function resizeIframe (obj, minHeight) {
    obj.style.height = '';
    var height = obj.contentWindow.document.body.scrollHeight;

    if (height < minHeight) {
      obj.style.height = minHeight + 'px';
    }
    else {
      obj.style.height = (height + 50) + 'px';
    }
  }
</script>
