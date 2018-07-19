<?php



namespace FI\Modules\Loans\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Models\LoanItem;
use FI\Modules\Loans\Requests\LoanStoreRequest;
use FI\Support\DateFormatter;

class LoanCopyController extends Controller
{
    public function create()
    {
        $loan = Loan::find(request('loan_id'));

        return view('loans._modal_copy')
            ->with('loan', $loan)
            ->with('groups', Group::getList())
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('loan_date', DateFormatter::format())
            ->with('user_id', auth()->user()->id);
    }

    public function store(LoanStoreRequest $request)
    {
        $client = Client::firstOrCreateByUniqueName($request->input('client_name'));

        $fromLoan = Loan::find($request->input('loan_id'));

        $toLoan = Loan::create([
            'client_id' => $client->id,
            'company_profile_id' => $request->input('company_profile_id'),
            'loan_date' => DateFormatter::unformat(request('loan_date')),
            'group_id' => $request->input('group_id'),
            'currency_code' => $fromLoan->currency_code,
            'exchange_rate' => $fromLoan->exchange_rate,
            'terms' => $fromLoan->terms,
            'footer' => $fromLoan->footer,
            'template' => $fromLoan->template,
            'summary' => $fromLoan->summary,
            'discount' => $fromLoan->discount,
        ]);

        foreach ($fromLoan->items as $item) {
            LoanItem::create([
                'loan_id' => $toLoan->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax_rate_id' => $item->tax_rate_id,
                'tax_rate_2_id' => $item->tax_rate_2_id,
                'display_order' => $item->display_order,
            ]);
        }

        // Copy the custom fields
        $custom = collect($fromLoan->custom)->except('loan_id')->toArray();
        $toLoan->custom->update($custom);

        return response()->json(['id' => $toLoan->id], 200);
    }
}
