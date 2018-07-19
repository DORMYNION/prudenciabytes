<?php

namespace FI\Modules\Loans\Support;

use FI\Support\Directory;

class LoanTemplates
{
    /**
     * Returns an array of loan templates.
     *
     * @return array
     */
    public static function lists()
    {
        $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/loans'));

        $customTemplates = Directory::listAssocContents(base_path('custom/templates/loan_templates'));

        return $defaultTemplates + $customTemplates;
    }
}
