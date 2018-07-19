<?php

namespace FI\Modules\Invests\Models;

use FI\Events\InvestItemSaving;
use FI\Events\InvestModified;
use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Model;

class InvestItem extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($investItem) {
            $investItem->amount()->delete();
        });

        static::deleted(function ($investItem) {
            if ($investItem->invest) {
                event(new InvestModified($investItem->invest));
            }
        });

        static::saving(function ($investItem) {
            event(new InvestItemSaving($investItem));
        });

        static::saved(function ($investItem) {
            event(new InvestModified($investItem->invest));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function amount()
    {
        return $this->hasOne('FI\Modules\Invests\Models\InvestItemAmount', 'item_id');
    }

    public function invest()
    {
        return $this->belongsTo('FI\Modules\Invests\Models\Invest');
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
        return CurrencyFormatter::format($this->attributes['price'], $this->invest->currency);
    }

    public function getFormattedDescriptionAttribute()
    {
        return nl2br($this->attributes['description']);
    }
}
