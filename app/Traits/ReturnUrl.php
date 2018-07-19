<?php

namespace FI\Traits;

trait ReturnUrl
{
    public function setReturnUrl()
    {
        session(['returnUrl' => request()->fullUrl()]);
    }

    public function getReturnUrl()
    {
        return session('returnUrl');
    }
}
