<?php


namespace FI\Support;

class FileNames
{
    public static function loan($loan)
    {
        return trans('fi.loan') . '_' . str_replace('/', '-', $loan->number) . '.pdf';
    }

    public static function invest($invest)
    {
        return trans('fi.invest') . '_' . str_replace('/', '-', $invest->number) . '.pdf';
    }
}
