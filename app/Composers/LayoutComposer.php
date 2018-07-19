<?php

namespace FI\Composers;

class LayoutComposer
{
    public function compose($view)
    {
        $view->with('userName', auth()->user()->name);
        $view->with('profileImageUrl', profileImageUrl(auth()->user()));
    }
}
