<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Exports\Support\Results;

use FI\Modules\Invests\Models\Invest;

class Invests implements SourceInterface
{
    public function getResults($params = [])
    {
        $invest = Invest::select('invests.number', 'invests.created_at', 'invests.updated_at', 'invests.expires_at',
            'invests.terms', 'invests.footer', 'invests.url_key', 'invests.currency_code', 'invests.exchange_rate',
            'invests.template', 'invests.summary', 'groups.name AS group', 'clients.name AS client_name',
            'clients.email AS client_email', 'clients.address AS client_address', 'clients.city AS client_city',
            'clients.state AS client_state', 'clients.zip AS client_zip', 'clients.country AS client_country',
            'users.name AS user_name', 'users.email AS user_email',
            'company_profiles.company AS company', 'company_profiles.address AS company_address',
            'company_profiles.city AS company_city', 'company_profiles.state AS company_state',
            'company_profiles.zip AS company_zip', 'company_profiles.country AS company_country',
            'invest_amounts.subtotal', 'invest_amounts.tax', 'invest_amounts.total')
            ->join('invest_amounts', 'invest_amounts.invest_id', '=', 'invests.id')
            ->join('clients', 'clients.id', '=', 'invests.client_id')
            ->join('groups', 'groups.id', '=', 'invests.group_id')
            ->join('users', 'users.id', '=', 'invests.user_id')
            ->join('company_profiles', 'company_profiles.id', '=', 'invests.company_profile_id')
            ->orderBy('number');

        return $invest->get()->toArray();
    }
}