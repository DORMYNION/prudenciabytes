<?php

/**

 *
 
 


 
 *

 */

namespace FI\Support\ProfileImage;

class ProfileImageFactory
{
    public static function create()
    {
        $class = 'FI\Support\ProfileImage\Drivers\\' . config('fi.profileImageDriver');

        return new $class;
    }

    public static function getDrivers()
    {
        $driverFiles = Directory::listContents(app_path('Support/ProfileImage/Drivers'));
        $drivers = [];

        foreach ($driverFiles as $driverFile) {
            $driver = str_replace('.php', '', $driverFile);

            $drivers[$driver] = $driver;
        }

        return $drivers;
    }
}