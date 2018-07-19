<?php

namespace FI\Support;

class Skins
{
    public static function lists()
    {
        $skins = Directory::listAssocContents(public_path('assets/dist/adminlte/css/skins'));

        unset($skins['_all-skins.css'], $skins['_all-skins.min.css']);

        foreach ($skins as $skin) {
            if (!strstr($skin, '.min.css')) {
                unset($skins[$skin]);
                continue;
            }

            $skins[$skin] = str_replace('skin-', '', $skins[$skin]);
            $skins[$skin] = str_replace('.min.css', '', $skins[$skin]);
        }

        return $skins;
    }
}
