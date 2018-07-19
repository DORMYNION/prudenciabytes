<?php

namespace FI\Modules\Invests\Support;

use FI\Events\LoanModified;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Loans\Models\LoanItem;
use FI\Support\Statuses\LoanStatuses;
use FI\Support\Statuses\InvestStatuses;

class InvestToLoan
{
    public function convert($invest, $loanDate, $dueAt, $groupId)
    {
        $record = [
            'client_id' => $invest->client_id,
            'loan_date' => $loanDate,
            'due_at' => $dueAt,
            'group_id' => $groupId,
            'number' => Group::generateNumber($groupId),
            'user_id' => $invest->user_id,
            'loan_status_id' => LoanStatuses::getStatusId('draft'),
            'terms' => ((config('fi.convertInvestTerms') == 'invest') ? $invest->terms : config('fi.loanTerms')),
            'footer' => $invest->footer,
            'currency_code' => $invest->currency_code,
            'exchange_rate' => $invest->exchange_rate,
            'summary' => $invest->summary,
            'discount' => $invest->discount,
            'company_profile_id' => $invest->company_profile_id,
        ];

        $toLoan = Loan::create($record);

        CustomField::copyCustomFieldValues($invest, $toLoan);

        $invest->loan_id = $toLoan->id;
        $invest->invest_status_id = InvestStatuses::getStatusId('approved');
        $invest->save();

        foreach ($invest->investItems as $item) {
            $itemRecord = [
                'loan_id' => $toLoan->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax_rate_id' => $item->tax_rate_id,
                'tax_rate_2_id' => $item->tax_rate_2_id,
                'display_order' => $item->display_order,
            ];

            LoanItem::create($itemRecord);
        }

        event(new LoanModified($toLoan));

        return $toLoan;
    }
}
