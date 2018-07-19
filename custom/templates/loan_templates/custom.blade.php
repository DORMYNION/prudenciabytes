<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ trans('fi.loan') }} #{{ $loan->number }}</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            color: #001028;
            background: #FFFFFF;
            font-family : DejaVu Sans, Helvetica, sans-serif;
            font-size: 12px;
            margin-bottom: 50px;
        }

        a {
            color: #5D6975;
            border-bottom: 1px solid currentColor;
            text-decoration: none;
        }

        h1 {
            color: #5D6975;
            font-size: 2.8em;
            line-height: 1.4em;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        th, .section-header {
            padding: 5px 10px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
            text-align: center;
        }

        td {
            padding: 10px;
        }

        table.alternate tr:nth-child(odd) td {
            background: #F5F5F5;
        }

        th.amount, td.amount {
            text-align: right;
        }

        .info {
            color: #5D6975;
            font-weight: bold;
        }

        .terms {
            padding: 10px;
        }

        .footer {
            position: fixed;
            height: 50px;
            width: 100%;
            bottom: 0;
            text-align: center;
        }

    </style>
</head>
<body>

<table>
    <tr>
        <td style="width: 50%;" valign="top">
            <h1>{{ mb_strtoupper(trans('fi.loan')) }}</h1>
            <span class="info">{{ mb_strtoupper(trans('fi.loan')) }} #</span>{{ $loan->number }}<br>
            <span class="info">{{ mb_strtoupper(trans('fi.issued')) }}</span> {{ $loan->formatted_created_at }}<br>
            <span class="info">{{ mb_strtoupper(trans('fi.due_date')) }}</span> {{ $loan->formatted_due_at }}<br><br>
            <span class="info">{{ mb_strtoupper(trans('fi.bill_to')) }}</span><br>{{ $loan->client->name }}<br>
            @if ($loan->client->address) {!! $loan->client->formatted_address !!}<br>@endif
        </td>
        <td style="width: 50%; text-align: right;" valign="top">
            {!! $loan->companyProfile->logo() !!}<br>
            {{ $loan->companyProfile->company }}<br>
            {!! $loan->companyProfile->formatted_address !!}<br>
            @if ($loan->companyProfile->phone) {{ $loan->companyProfile->phone }}<br>@endif
            @if ($loan->user->email) <a href="mailto:{{ $loan->user->email }}">{{ $loan->user->email }}</a>@endif
        </td>
    </tr>
</table>

<table class="alternate">
    <thead>
    <tr>
        <th>{{ mb_strtoupper(trans('fi.product')) }}</th>
        <th>{{ mb_strtoupper(trans('fi.description')) }}</th>
        <th class="amount">{{ mb_strtoupper(trans('fi.quantity')) }}</th>
        <th class="amount">{{ mb_strtoupper(trans('fi.price')) }}</th>
        <th class="amount">{{ mb_strtoupper(trans('fi.total')) }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($loan->items as $item)
        <tr>
            <td>{!! $item->name !!}</td>
            <td>{!! $item->formatted_description !!}</td>
            <td nowrap class="amount">{{ $item->formatted_quantity }}</td>
            <td nowrap class="amount">{{ $item->formatted_price }}</td>
            <td nowrap class="amount">{{ $item->amount->formatted_subtotal }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('fi.subtotal')) }}</td>
        <td class="amount">{{ $loan->amount->formatted_subtotal }}</td>
    </tr>

    @if ($loan->discount > 0)
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper(trans('fi.discount')) }}</td>
            <td class="amount">{{ $loan->amount->formatted_discount }}</td>
        </tr>
    @endif

    @foreach ($loan->summarized_taxes as $tax)
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper($tax->name) }} ({{ $tax->percent }})</td>
            <td class="amount">{{ $tax->total }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('fi.total')) }}</td>
        <td class="amount">{{ $loan->amount->formatted_total }}</td>
    </tr>
    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('fi.paid')) }}</td>
        <td class="amount">{{ $loan->amount->formatted_paid }}</td>
    </tr>
    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('fi.balance')) }}</td>
        <td class="amount">{{ $loan->amount->formatted_balance }}</td>
    </tr>
    </tbody>
</table>

@if ($loan->terms)
    <div class="section-header">{{ mb_strtoupper(trans('fi.terms_and_conditions')) }}</div>
    <div class="terms">{!! $loan->formatted_terms !!}</div>
@endif

<div class="footer">{!! $loan->formatted_footer !!}</div>

</body>
</html>