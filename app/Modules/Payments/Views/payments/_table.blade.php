<table class="table table-hover">

    <thead>
    <tr>
        <th>
            <div class="btn-group"><input type="checkbox" id="bulk-select-all"></div>
        </th>
        <th>{!! Sortable::link('paid_at', trans('fi.payment_date'), 'payments') !!}</th>
        <th>{!! Sortable::link('loans.number', trans('fi.loan'), 'payments') !!}</th>
        <th>{!! Sortable::link('loans.loan_date', trans('fi.date'), 'payments') !!}</th>
        <th>{!! Sortable::link('clients.name', trans('fi.client'), 'payments') !!}</th>
        <th>{!! Sortable::link('loans.summary', trans('fi.summary'), 'payments') !!}</th>
        <th>{!! Sortable::link('amount', trans('fi.amount'), 'payments') !!}</th>
        <th>{!! Sortable::link('payment_methods.name', trans('fi.payment_method'), 'payments') !!}</th>
        <th>{!! Sortable::link('note', trans('fi.note'), 'payments') !!}</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($payments as $payment)
        <tr>
            <td><input type="checkbox" class="bulk-record" data-id="{{ $payment->id }}"></td>
            <td>{{ $payment->formatted_paid_at }}</td>
            <td><a href="{{ route('loans.edit', [$payment->loan_id]) }}">{{ $payment->loan->number }}</a></td>
            <td>{{ $payment->loan->formatted_created_at }}</td>
            <td>
                <a href="{{ route('clients.show', [$payment->loan->client_id]) }}">{{ $payment->loan->client->name }}</a>
            </td>
            <td>{{ $payment->loan->summary }}</td>
            <td>{{ $payment->formatted_amount }}</td>
            <td>@if ($payment->paymentMethod) {{ $payment->paymentMethod->name }} @endif</td>
            <td>{{ $payment->note }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('payments.edit', [$payment->id]) }}"><i
                                        class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                        <li><a href="{{ route('loans.pdf', [$payment->loan->id]) }}" target="_blank"
                               id="btn-pdf-loan"><i class="fa fa-print"></i> {{ trans('fi.loan') }}</a></li>
                        @if (config('fi.mailConfigured'))
                            <li><a href="javascript:void(0)" class="email-payment-receipt"
                                   data-payment-id="{{ $payment->id }}" data-redirect-to="{{ request()->fullUrl() }}"><i
                                            class="fa fa-envelope"></i> {{ trans('fi.email_payment_receipt') }}</a></li>
                        @endif
                        <li><a href="{{ route('payments.delete', [$payment->id]) }}"
                               onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                        class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>