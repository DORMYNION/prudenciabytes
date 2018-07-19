<?php

namespace FI\Support;

class Frequency
{
    /**
     * Returns a list of frequencies for recurring loans.
     *
     * @return array
     */
    public static function lists()
    {
        return [
            '1' => trans('fi.days'),
            '2' => trans('fi.weeks'),
            '3' => trans('fi.months'),
            '4' => trans('fi.years'),
        ];
    }
}
