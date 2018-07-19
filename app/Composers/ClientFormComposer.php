<?php

namespace FI\Composers;

use FI\Modules\Currencies\Models\Currency;
use FI\Modules\Loans\Support\LoanTemplates;
use FI\Modules\Invests\Support\InvestTemplates;
use FI\Support\Languages;

class ClientFormComposer
{
    public function compose($view)
    {
        $view->with('currencies', Currency::getList())
            ->with('loanTemplates', LoanTemplates::lists())
            ->with('investTemplates', InvestTemplates::lists())
            ->with('loanTemplates', LoanTemplates::lists())
            ->with('investTemplates', InvestTemplates::lists())
            ->with('languages', Languages::listLanguages());
    }
}
