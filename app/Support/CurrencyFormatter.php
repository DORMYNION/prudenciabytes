<?php

namespace FI\Support;

class CurrencyFormatter extends NumberFormatter
{
    /**
     * Formats currency according to FI config.
     *
     * @param  float   $amount
     * @param  object  $currency
     * @param  integer $decimalPlaces
     * @return string
     */
    public static function format($amount, $currency = null, $decimalPlaces = null)
    {
        $currency = ($currency) ?: config('fi.currency');
        $decimalPlaces = ($decimalPlaces) ?: config('fi.amountDecimals');

        $amount = parent::format($amount, $currency, $decimalPlaces);

        if ($currency->placement == 'before') {
            return $currency->symbol . $amount;
        }

        return $amount . $currency->symbol;
    }
}
