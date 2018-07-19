<?php


namespace FI\Modules\API\Controllers;

use FI\Modules\API\Requests\APILoanItemRequest;
use FI\Modules\API\Requests\APILoanStoreRequest;
use FI\Modules\Clients\Models\Client;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Models\LoanItem;
use FI\Modules\Users\Models\User;

class ApiLoanController extends ApiController
{
    public function lists()
    {
        $loans = Loan::select('loans.*')
            ->with(['items.amount', 'client', 'amount', 'currency'])
            ->status(request('status'))
            ->sortable(['loan_date' => 'desc', 'LENGTH(number)' => 'desc', 'number' => 'desc'])
            ->paginate(config('fi.resultsPerPage'));

        return response()->json($loans);
    }

    public function show()
    {
        return response()->json(Loan::with(['items.amount', 'client', 'amount', 'currency'])->find(request('id')));
    }

    public function store(APILoanStoreRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        $input['user_id'] = User::where('client_id', 0)->where('api_public_key', $request->input('key'))->first()->id;

        $input['client_id'] = Client::firstOrCreateByUniqueName($request->input('client_name'))->id;

        unset($input['client_name']);

        return response()->json(Loan::create($input));
    }

    public function addItem(APILoanItemRequest $request)
    {
        $input = $request->except('key', 'signature', 'timestamp', 'endpoint');

        LoanItem::create($input);
    }

    public function delete()
    {
        $validator = $this->validator->make(['id' => request('id')], ['id' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 400);
        }

        if (Loan::find(request('id'))) {
            Loan::destroy(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}
