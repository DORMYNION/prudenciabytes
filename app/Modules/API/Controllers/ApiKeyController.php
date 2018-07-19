<?php

namespace FI\Modules\API\Controllers;

class ApiKeyController extends ApiController
{
    public function generateKeys()
    {
        return response()->json(['api_public_key' => str_random(32), 'api_secret_key' => str_random(32)]);
    }
}
