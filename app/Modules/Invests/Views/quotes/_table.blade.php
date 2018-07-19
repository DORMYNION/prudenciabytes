<table class="table table-hover" style="height: 100%;">

    <thead>
    <tr>
        <th>
            <div class="btn-group"><input type="checkbox" id="bulk-select-all"></div>
        </th>
        <th class="hidden-sm hidden-xs">{{ trans('fi.status') }}</th>
        <th>{!! Sortable::link('number', trans('fi.invest'), 'invests') !!}</th>
        <th class="hidden-xs">{!! Sortable::link('invest_date', trans('fi.date'), 'invests') !!}</th>
        <th class="hidden-sm hidden-xs">{!! Sortable::link('expires_at', trans('fi.expires'), 'invests') !!}</th>
        <th>{!! Sortable::link('clients.name', trans('fi.client'), 'invests') !!}</th>
        <th class="hidden-sm hidden-xs">{!! Sortable::link('summary', trans('fi.summary'), 'invests') !!}</th>
        <th style="text-align: right; padding-right: 25px;">{!! Sortable::link('invest_amounts.total', trans('fi.total'), 'invests') !!}</th>
        <th>{{ trans('fi.loand') }}</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($invests as $invest)
        <tr>
            <td><input type="checkbox" class="bulk-record" data-id="{{ $invest->id }}"></td>
            <td class="hidden-sm hidden-xs">
                <span class="label label-{{ $statuses[$invest->invest_status_id] }}">{{ trans('fi.' . $statuses[$invest->invest_status_id]) }}</span>
                @if ($invest->viewed)
                    <span class="label label-success">{{ trans('fi.viewed') }}</span>
                @else
                    <span class="label label-default">{{ trans('fi.not_viewed') }}</span>
                @endif
            </td>
            <td><a href="{{ route('invests.edit', [$invest->id]) }}"
                   title="{{ trans('fi.edit') }}">{{ $invest->number }}</a></td>
            <td class="hidden-xs">{{ $invest->formatted_invest_date }}</td>
            <td class="hidden-sm hidden-xs">{{ $invest->formatted_expires_at }}</td>
            <td><a href="{{ route('clients.show', [$invest->client->id]) }}"
                   title="{{ trans('fi.view_client') }}">{{ $invest->client->unique_name }}</a></td>
            <td class="hidden-sm hidden-xs">{{ $invest->summary }}</td>
            <td style="text-align: right; padding-right: 25px;">{{ $invest->amount->formatted_total }}</td>
            <td class="hidden-xs">
                @if ($invest->loan)
                    <a href="{{ route('loans.edit', [$invest->loan_id]) }}">{{ trans('fi.yes') }}</a>
                @else
                    {{ trans('fi.no') }}
                @endif
            </td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('invests.edit', [$invest->id]) }}"><i
                                        class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                        <li><a href="{{ route('invests.pdf', [$invest->id]) }}" target="_blank" id="btn-pdf-invest"><i
                                        class="fa fa-print"></i> {{ trans('fi.pdf') }}</a></li>
                        <li><a href="javascript:void(0)" class="email-invest" data-invest-id="{{ $invest->id }}"
                               data-redirect-to="{{ request()->fullUrl() }}"><i
                                        class="fa fa-envelope"></i> {{ trans('fi.email') }}</a></li>
                        <li><a href="{{ route('clientCenter.public.invest.show', [$invest->url_key]) }}" target="_blank"
                               id="btn-public-invest"><i class="fa fa-globe"></i> {{ trans('fi.public') }}</a></li>
                        <li><a href="{{ route('invests.delete', [$invest->id]) }}"
                               onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                        class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>