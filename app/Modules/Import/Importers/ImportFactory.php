<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Import\Importers;

class ImportFactory
{
    public static function create($importType)
    {
        switch ($importType) {
            case 'clients':
                return app()->make('FI\Modules\Import\Importers\ClientImporter');
            case 'invests':
                return app()->make('FI\Modules\Import\Importers\InvestImporter');
            case 'loans':
                return app()->make('FI\Modules\Import\Importers\LoanImporter');
            case 'payments':
                return app()->make('FI\Modules\Import\Importers\PaymentImporter');
            case 'loanItems':
                return app()->make('FI\Modules\Import\Importers\LoanItemImporter');
            case 'investItems':
                return app()->make('FI\Modules\Import\Importers\InvestItemImporter');
            case 'itemLookups':
                return app()->make('FI\Modules\Import\Importers\ItemLookupImporter');
            case 'expenses':
                return app('FI\Modules\Import\Importers\ExpenseImporter');
        }
    }
}