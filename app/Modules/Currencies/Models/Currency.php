<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Currencies\Models;

use FI\Modules\Clients\Models\Client;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Invests\Models\Invest;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use Sortable;

    protected $table = 'currencies';

    protected $sortable = ['code', 'name', 'symbol', 'placement', 'decimal', 'thousands'];

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    public static function getList()
    {
        return self::orderBy('name')->pluck('name', 'code')->all();
    }

    public function getInUseAttribute()
    {
        if ($this->code == config('fi.baseCurrency')) {
            return true;
        }

        if (Client::where('currency_code', '=', $this->code)->count()) {
            return true;
        }

        if (Invest::where('currency_code', '=', $this->code)->count()) {
            return true;
        }

        if (Loan::where('currency_code', '=', $this->code)->count()) {
            return true;
        }

        return false;
    }

    public function getFormattedPlacementAttribute()
    {
        return ($this->placement == 'before') ? trans('fi.before_amount') : trans('fi.after_amount');
    }
}