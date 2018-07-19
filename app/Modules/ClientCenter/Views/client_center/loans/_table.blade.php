<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ trans('fi.status') }}</th>
        <th>{{ trans('fi.loan') }}</th>
        <th>{{ trans('fi.date') }}</th>
        <th>{{ trans('fi.due') }}</th>
        <th>{{ trans('fi.summary') }}</th>
        <th>{{ trans('fi.total') }}</th>
        <th>{{ trans('fi.balance') }}</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($loans as $loan)
        <tr>
            <td>
                <span class="label label-{{ $loanStatuses[$loan->loan_status_id] }}">{{ trans('fi.' . $loanStatuses[$loan->loan_status_id]) }}</span>
                @if ($loan->viewed)
                    <span class="label label-success">{{ trans('fi.viewed') }}</span>
                @else
                    <span class="label label-default">{{ trans('fi.not_viewed') }}</span>
                @endif
            </td>
            <td>{{ $loan->number }}</td>
            <td>{{ $loan->formatted_created_at }}</td>
            <td>{{ $loan->formatted_due_at }}</td>
            <td>{{ $loan->summary }}</td>
            <td>{{ $loan->amount->formatted_total }}</td>
            <td>{{ $loan->amount->formatted_balance }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('clientCenter.public.loan.pdf', [$loan->url_key]) }}"
                               target="_blank"><i class="fa fa-print"></i> {{ trans('fi.pdf') }}</a></li>
                        <li><a href="{{ route('clientCenter.public.loan.show', [$loan->url_key]) }}"
                               target="_blank"><i class="fa fa-search"></i> {{ trans('fi.view') }}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>