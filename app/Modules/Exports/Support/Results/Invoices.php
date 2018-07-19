<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Exports\Support\Results;

use FI\Modules\Loans\Models\Loan;

class Loans implements SourceInterface
{
    public function getResults($params = [])
    {
        $loan = Loan::select('loans.number', 'loans.created_at', 'loans.updated_at', 'loans.loan_date',
            'loans.due_at', 'loans.terms', 'loans.footer', 'loans.url_key', 'loans.currency_code',
            'loans.exchange_rate', 'loans.template', 'loans.summary', 'groups.name AS group', 'clients.name AS client_name',
            'clients.email AS client_email', 'clients.address AS client_address', 'clients.city AS client_city',
            'clients.state AS client_state', 'clients.zip AS client_zip', 'clients.country AS client_country',
            'users.name AS user_name', 'users.email AS user_email',
            'company_profiles.company AS company', 'company_profiles.address AS company_address',
            'company_profiles.city AS company_city', 'company_profiles.state AS company_state',
            'company_profiles.zip AS company_zip', 'company_profiles.country AS company_country',
            'loan_amounts.subtotal', 'loan_amounts.tax', 'loan_amounts.total',
            'loan_amounts.paid', 'loan_amounts.balance')
            ->join('loan_amounts', 'loan_amounts.loan_id', '=', 'loans.id')
            ->join('clients', 'clients.id', '=', 'loans.client_id')
            ->join('groups', 'groups.id', '=', 'loans.group_id')
            ->join('users', 'users.id', '=', 'loans.user_id')
            ->join('company_profiles', 'company_profiles.id', '=', 'loans.company_profile_id')
            ->orderBy('number');

        return $loan->get()->toArray();
    }
}