<script type="text/javascript">
  var handler = StripeCheckout.configure({
    key: '{{ $driver->getSetting('publishableKey') }}',
    image: @if ($loan->companyProfile->logo_url) '{{ $loan->companyProfile->logo_url }}',
      @else 'https://stripe.com/img/documentation/checkout/marketplace.png',
      @endif
      locale: 'auto',
    token: function (token) {
      window.location = '{{ route('merchant.returnUrl', [$driver->getName(), $urlKey]) }}?token=' + token.id;
    }
  });

  handler.open({
    name: '{!! $loan->companyProfile->company !!}',
    description: '{{ trans('fi.loan') }} #{{ $loan->number }}',
    email: '{{ $loan->client->email }}',
    billingAddress: true,
    zipCode: true,
    amount: {{ $loan->amount->balance * 100 }},
    currency: '{{ $loan->currency_code }}'
  });

  window.addEventListener('popstate', function () {
    handler.close();
  });

</script>