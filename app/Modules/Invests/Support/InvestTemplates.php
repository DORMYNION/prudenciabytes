<?php

namespace FI\Modules\Invests\Support;

use FI\Support\Directory;

class InvestTemplates
{
    /**
     * Returns an array of invest templates.
     *
     * @return array
     */
    public static function lists()
    {
        $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/invests'));

        $customTemplates = Directory::listAssocContents(base_path('custom/templates/invest_templates'));

        return $defaultTemplates + $customTemplates;
    }
}
