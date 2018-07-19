<?php

/**

 *





 *

 */

namespace FI\Modules\Addons\Models;

use FI\Support\Migrations;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $table = 'addons';

    protected $guarded = ['id'];

    public function getHasPendingMigrationsAttribute()
    {
        $migrations = new Migrations();

        if ($migrations->getPendingMigrations(addon_path($this->path . '/Migrations'))) {
            return true;
        }

        return false;
    }
}
