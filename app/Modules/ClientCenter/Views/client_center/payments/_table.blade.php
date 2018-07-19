<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ trans('fi.date') }}</th>
        <th>{{ trans('fi.loan') }}</th>
        <th>{{ trans('fi.summary') }}</th>
        <th>{{ trans('fi.amount') }}</th>
        <th>{{ trans('fi.payment_method') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->formatted_paid_at }}</td>
            <td>{{ $payment->loan->number }}</td>
            <td>{{ $payment->loan->summary }}</td>
            <td>{{ $payment->formatted_amount }}</td>
            <td>{{ $payment->paymentMethod->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>