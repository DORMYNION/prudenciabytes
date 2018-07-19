<?php

/**

 *
 
 


 
 *

 */

namespace FI\Support\PDF;

use FI\Support\Directory;

class PDFFactory
{
    public static function create()
    {
        $class = 'FI\Support\PDF\Drivers\\' . config('fi.pdfDriver');

        return new $class;
    }

    public static function getDrivers()
    {
        $driverFiles = Directory::listContents(app_path('Support/PDF/Drivers'));
        $drivers = [];

        foreach ($driverFiles as $driverFile) {
            $driver = str_replace('.php', '', $driverFile);

            $drivers[$driver] = $driver;
        }

        return $drivers;
    }
}