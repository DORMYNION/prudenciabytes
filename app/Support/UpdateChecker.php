<?php

namespace FI\Support;

class UpdateChecker
{
    protected $currentVersion;

    public function __construct()
    {
        $check_url = 'https://ids.loanplane.com/updatecheck?cv=' . config('fi.version');
        $this->currentVersion = file_get_contents($check_url);
    }

    /**
     * Check to see if there is a newer version available for download.
     *
     * @return boolean
     */
    public function updateAvailable()
    {
        if (str_replace('-', '', $this->currentVersion) > str_replace('-', '', config('fi.version'))) {
            return true;
        }

        return false;
    }

    /**
     * Getter for current version.
     *
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }
}
