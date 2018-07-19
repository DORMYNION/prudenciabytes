<table class="table table-hover">
    <thead>
    <tr>
        <th>{{ trans('fi.status') }}</th>
        <th>{{ trans('fi.invest') }}</th>
        <th>{{ trans('fi.date') }}</th>
        <th>{{ trans('fi.expires') }}</th>
        <th>{{ trans('fi.summary') }}</th>
        <th>{{ trans('fi.total') }}</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($invests as $invest)
        <tr>
            <td>
                <span class="label label-{{ $investStatuses[$invest->invest_status_id] }}">{{ trans('fi.' . $investStatuses[$invest->invest_status_id]) }}</span>
                @if ($invest->viewed)
                    <span class="label label-success">{{ trans('fi.viewed') }}</span>
                @else
                    <span class="label label-default">{{ trans('fi.not_viewed') }}</span>
                @endif
            </td>
            <td>{{ $invest->number }}</td>
            <td>{{ $invest->formatted_created_at }}</td>
            <td>{{ $invest->formatted_expires_at }}</td>
            <td>{{ $invest->summary }}</td>
            <td>{{ $invest->amount->formatted_total }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('clientCenter.public.invest.pdf', [$invest->url_key]) }}" target="_blank"><i
                                        class="fa fa-print"></i> {{ trans('fi.pdf') }}</a></li>
                        <li><a href="{{ route('clientCenter.public.invest.show', [$invest->url_key]) }}"
                               target="_blank"><i class="fa fa-search"></i> {{ trans('fi.view') }}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>