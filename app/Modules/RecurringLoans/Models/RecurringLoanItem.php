<?php

namespace FI\Modules\RecurringLoans\Models;

use FI\Events\RecurringLoanItemSaving;
use FI\Events\RecurringLoanModified;
use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Model;

class RecurringLoanItem extends Model
{
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($recurringLoanItem) {
            event(new RecurringLoanItemSaving($recurringLoanItem));
        });

        static::saved(function ($recurringLoanItem) {
            event(new RecurringLoanModified($recurringLoanItem->recurringLoan));
        });

        static::deleting(function ($recurringLoanItem) {
            $recurringLoanItem->amount()->delete();
        });

        static::deleted(function ($recurringLoanItem) {
            if ($recurringLoanItem->recurringLoan) {
                event(new RecurringLoanModified($recurringLoanItem->recurringLoan));
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
        return $this->hasOne('FI\Modules\RecurringLoans\Models\RecurringLoanItemAmount', 'item_id');
    }

    public function recurringLoan()
    {
        return $this->belongsTo('FI\Modules\RecurringLoans\Models\RecurringLoan');
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
        return CurrencyFormatter::format($this->attributes['price'], $this->recurringLoan->currency);
    }

    public function getFormattedDescriptionAttribute()
    {
        return nl2br($this->attributes['description']);
    }
}
