<?php

namespace FI\Composers;

class SkinComposer
{
    public function compose($view)
    {
        $skin = (config('fi.skin') ?: 'skin-invoiceplane.min.css');
        $view->with('skin', $skin);
        $view->with('skinClass', str_replace('.min.css', '', $skin));
    }
}
