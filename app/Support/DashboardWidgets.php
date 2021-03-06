<?php

namespace FI\Support;

class DashboardWidgets
{
    public static function listsByOrder()
    {
        $widgets = self::lists();
        $return = [];
        $unassigned = 100;

        foreach ($widgets as $widget) {
            if (!$displayOrder = config('fi.widgetDisplayOrder' . $widget)) {
                $displayOrder = $unassigned;
                $unassigned++;
            }

            $return[str_pad($displayOrder, 3, 0, STR_PAD_LEFT) . '-' . $widget] = $widget;
        }

        ksort($return);

        return $return;
    }

    public static function lists()
    {
        return Directory::listContents(__DIR__ . '/../Widgets/Dashboard');
    }
}
