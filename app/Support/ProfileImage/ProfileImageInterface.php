<?php

/**

 *
 
 


 
 *

 */

namespace FI\Support\ProfileImage;

use FI\Modules\Users\Models\User;

interface ProfileImageInterface
{
    public function getProfileImageUrl(User $user);
}