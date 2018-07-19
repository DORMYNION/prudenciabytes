<?php

namespace FI\Modules\API\Controllers;

use FI\Modules\API\Requests\APIInvestItemRequest;
use FI\Modules\API\Requests\APIInvestStoreRequest;
use FI\Modules\Clients\Models\Client;
use FI\Modules\Invests\Models\Invest;
use FI\Modules\Invests\Models\InvestItem;
use FI\Modules\Users\Models\User;

class ApiInvestController extends ApiController
{
    public function lists()
    {
        $invests = Invest::select('invests.*')
            ->with(['items.amount', 'client', 'amount', 'currency'])
            ->status(request('status'))
            ->sortable(['invest_date' => 'desc', 'LENGTH(number)' => 'desc', 'number' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return response()->json($invests);
    }

    public function show()
    {
        return response()->json(Invest::with(['items.amount', 'client', 'amount', 'currency'])->find(request('id')));
    }

    public function store(APIInvestStoreRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        $input['user_id'] = User::where('client_id', 0)->where('api_public_key', $request->input('key'))->first()->id;

        $input['client_id'] = Client::firstOrCreateByUniqueName(request('client_name'))->id;

        unset($input['client_name']);

        return response()->json(Invest::create($input));
    }

    public function addItem(APIInvestItemRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        InvestItem::create($input);
    }

    public function delete()
    {
        $validator = $this->validator->make(['id' => request('id')], ['id' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 400);
        }

        if (Invest::find(request('id'))) {
            Invest::destroy(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}
