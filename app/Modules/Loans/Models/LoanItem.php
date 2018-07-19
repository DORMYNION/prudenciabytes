<?php

namespace FI\Modules\Loans\Models;

use FI\Events\LoanItemSaving;
use FI\Events\LoanModified;
use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    protected $guarded = ['id', 'item_id'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($loanItem) {
            event(new LoanItemSaving($loanItem));
        });

        static::saved(function ($loanItem) {
            event(new LoanModified($loanItem->loan));
        });

        static::deleting(function ($loanItem) {
            $loanItem->amount()->delete();
        });

        static::deleted(function ($loanItem) {
            if ($loanItem->loan) {
                event(new LoanModified($loanItem->loan));
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function amount()
    {
        return $this->hasOne('FI\Modules\Loans\Models\LoanItemAmount', 'item_id');
    }

    public function loan()
    {
        return $this->belongsTo('FI\Modules\Loans\Models\Loan');
    }

    public function taxRate()
    {
        return $this->belongsTo('FI\Modules\TaxRates\Models\TaxRate');
    }

    public function taxRate2()
    {
        return $this->belongsTo('FI\Modules\TaxRates\Models\TaxRate', 'tax_rate_2_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedQuantityAttribute()
    {
        return NumberFormatter::format($this->attributes['quantity']);
    }

    public function getFormattedNumericPriceAttribute()
    {
        return NumberFormatter::format($this->attributes['price']);
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['price'], $this->loan->currency);
    }

    public function getFormattedDescriptionAttribute()
    {
        return nl2br($this->attributes['description']);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereIn('loan_id', function ($query) use ($from, $to) {
            $query->select('id')
                ->from('loans')
                ->where('loan_date', '>=', $from)
                ->where('loan_date', '<=', $to);
        });
    }
}
