<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\CompanyProfiles\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;

class LogoController extends Controller
{
    public function logo($id)
    {
        $companyProfile = CompanyProfile::find($id);

        if ($companyProfile->logo) {
            return response(file_get_contents(storage_path($companyProfile->logo)), 200)->header('Content-Type', 'image/jpeg');
        }

        return null;
    }
}