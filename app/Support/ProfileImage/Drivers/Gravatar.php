<?php

/**

 *
 
 


 
 *

 */

namespace FI\Support\ProfileImage\Drivers;

use FI\Modules\Users\Models\User;
use FI\Support\ProfileImage\ProfileImageInterface;

class Gravatar implements ProfileImageInterface
{
    public function getProfileImageUrl(User $user)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($user->email)) . '?d=mm';
    }
}