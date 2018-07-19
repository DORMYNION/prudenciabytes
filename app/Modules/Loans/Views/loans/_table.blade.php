<table class="table table-hover">

    <thead>
    <tr>
        <th>
            <div class="btn-group"><input type="checkbox" id="bulk-select-all"></div>
        </th>
        <th class="hidden-sm hidden-xs">{{ trans('fi.status') }}</th>
        <th>{!! Sortable::link('number', trans('ln.loan'), 'loans') !!}</th>
        <th class="hidden-xs">{!! Sortable::link('loan_date', trans('fi.date'), 'loans') !!}</th>
        <th class="hidden-md hidden-sm hidden-xs">{!! Sortable::link('due_at', trans('fi.due'), 'loans') !!}</th>
        <th>{!! Sortable::link('clients.name', trans('fi.client'), 'loans') !!}</th>
        <th class="hidden-sm hidden-xs">{!! Sortable::link('summary', trans('fi.summary'), 'loans') !!}</th>
        <th style="text-align: right; padding-right: 25px;">{!! Sortable::link('loan_amounts.total', trans('fi.total'), 'loans') !!}</th>
        <th class="hidden-sm hidden-xs"
            style="text-align: right; padding-right: 25px;">{!! Sortable::link('loan_amounts.balance', trans('fi.balance'), 'loans') !!}</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($loans as $loan)
        <tr>
            <td><input type="checkbox" class="bulk-record" data-id="{{ $loan->id }}"></td>
            <td class="hidden-sm hidden-xs">
                <span class="label label-{{ $statuses[$loan->loan_status_id] }}">{{ trans('fi.' . $statuses[$loan->loan_status_id]) }}</span>
                @if ($loan->viewed)
                    <span class="label label-success">{{ trans('fi.viewed') }}</span>
                @else
                    <span class="label label-default">{{ trans('fi.not_viewed') }}</span>
                @endif
            </td>
            <td><a href="{{ route('loans.edit', [$loan->id]) }}"
                   title="{{ trans('fi.edit') }}">{{ $loan->number }}</a></td>
            <td class="hidden-xs">{{ $loan->formatted_loan_date }}</td>
            <td class="hidden-md hidden-sm hidden-xs"
                @if ($loan->isOverdue) style="color: red; font-weight: bold;" @endif>{{ $loan->formatted_due_at }}</td>
            <td><a href="{{ route('clients.show', [$loan->client->id]) }}"
                   title="{{ trans('fi.view_client') }}">{{ $loan->client->unique_name }}</a></td>
            <td class="hidden-sm hidden-xs">{{ $loan->summary }}</td>
            <td style="text-align: right; padding-right: 25px;">{{ $loan->amount->formatted_total }}</td>
            <td class="hidden-sm hidden-xs"
                style="text-align: right; padding-right: 25px;">{{ $loan->amount->formatted_balance }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('loans.edit', [$loan->id]) }}"><i
                                        class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                        <li><a href="{{ route('loans.pdf', [$loan->id]) }}" target="_blank"
                               id="btn-pdf-loan"><i class="fa fa-print"></i> {{ trans('fi.pdf') }}</a></li>
                        <li><a href="javascript:void(0)" class="email-loan" data-loan-id="{{ $loan->id }}"
                               data-redirect-to="{{ request()->fullUrl() }}"><i
                                        class="fa fa-envelope"></i> {{ trans('fi.email') }}</a></li>
                        <li><a href="{{ route('clientCenter.public.loan.show', [$loan->url_key]) }}"
                               target="_blank" id="btn-public-loan"><i
                                        class="fa fa-globe"></i> {{ trans('fi.public') }}</a></li>
                        @if ($loan->isPayable or config('fi.allowPaymentsWithoutBalance'))
                            <li><a href="javascript:void(0)" id="btn-enter-payment" class="enter-payment"
                                   data-loan-id="{{ $loan->id }}"
                                   data-loan-balance="{{ $loan->amount->formatted_numeric_balance }}"
                                   data-redirect-to="{{ request()->fullUrl() }}"><i
                                            class="fa fa-credit-card"></i> {{ trans('fi.enter_payment') }}</a></li>
                        @endif
                        <li><a href="{{ route('loans.delete', [$loan->id]) }}"
                               onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                        class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
