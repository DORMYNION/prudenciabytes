<table class="table table-hover">

    <thead>
    <tr>
        <th>{!! Sortable::link('id', trans('fi.id'), 'recurring_loans') !!}</th>
        <th>{!! Sortable::link('clients.name', trans('fi.client'), 'recurring_loans') !!}</th>
        <th class="hidden-sm hidden-xs">{!! Sortable::link('summary', trans('fi.summary'), 'recurring_loans') !!}</th>
        <th>{!! Sortable::link('next_date', trans('fi.next_date'), 'recurring_loans') !!}</th>
        <th>{!! Sortable::link('stop_date', trans('fi.stop_date'), 'recurring_loans') !!}</th>
        <th>{{ trans('fi.every') }}</th>
        <th style="text-align: right; padding-right: 25px;">{!! Sortable::link('recurring_loan_amounts.total', trans('fi.total'), 'recurring_loans') !!}</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($recurringLoans as $recurringLoan)
        <tr>
            <td>
                <a href="{{ route('recurringLoans.edit', [$recurringLoan->id]) }}"
                   title="{{ trans('fi.edit') }}">{{ $recurringLoan->id }}</a>
            </td>
            <td>
                <a href="{{ route('clients.show', [$recurringLoan->client->id]) }}"
                   title="{{ trans('fi.view_client') }}">{{ $recurringLoan->client->unique_name }}</a>
            </td>
            <td class="hidden-sm hidden-xs">{{ $recurringLoan->summary }}</td>
            <td>{{ $recurringLoan->formatted_next_date }}</td>
            <td>{{ $recurringLoan->formatted_stop_date }}</td>
            <td>{{ $recurringLoan->recurring_frequency . ' ' . $frequencies[$recurringLoan->recurring_period] }}</td>
            <td style="text-align: right; padding-right: 25px;">{{ $recurringLoan->amount->formatted_total }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('recurringLoans.edit', [$recurringLoan->id]) }}"><i
                                        class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                        <li><a href="{{ route('recurringLoans.delete', [$recurringLoan->id]) }}"
                               onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                        class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>