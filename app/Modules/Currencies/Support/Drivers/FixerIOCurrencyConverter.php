<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Currencies\Support\Drivers;

class FixerIOCurrencyConverter
{
    /**
     * Returns the currency conversion rate.
     *
     * @param  string $from
     * @param  string $to
     * @return float
     */
    public function convert($from, $to)
    {
        try {
            $result = json_decode(file_get_contents('https://api.fixer.io/latest?base=' . $from . '&symbols=' . $to), true);

            return $result['rates'][strtoupper($to)];
        } catch (\Exception $e) {
            return '1.0000000';
        }

    }
}