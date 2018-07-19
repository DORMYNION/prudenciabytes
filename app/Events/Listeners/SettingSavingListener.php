<?php

namespace FI\Events\Listeners;

use FI\Events\SettingSaving;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;

class SettingSavingListener
{
    public function __construct()
    {
        //
    }

    public function handle(SettingSaving $event)
    {
        if ($event->setting->setting_key == 'loanTemplate' or $event->setting->setting_key == 'investTemplate') {
            $original = $event->setting->getOriginal();

            if (isset($original['setting_value']) and $original['setting_value'] !== $event->setting->setting_value) {
                $templateType = $event->setting->setting_key;
                $originalTemplate = $original['setting_value'];
                $newTemplate = $event->setting->setting_value;

                if ($templateType == 'loanTemplate') {
                    CompanyProfile::whereNull('loan_template')->orWhere('loan_template', $originalTemplate)->orWhere('loan_template', '')->update(['loan_template' => $newTemplate]);
                } elseif ($templateType == 'investTemplate') {
                    CompanyProfile::whereNull('invest_template')->orWhere('invest_template', $originalTemplate)->orWhere('invest_template', '')->update(['invest_template' => $newTemplate]);
                }
            }
        }
    }
}
