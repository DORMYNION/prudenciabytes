<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Currencies\Support;

class CurrencyConverterFactory
{
    public static function create()
    {
        $class = 'FI\Modules\Currencies\Support\Drivers\\' . config('fi.currencyConversionDriver');

        return new $class;
    }
}