<?php

namespace FI\Modules\API\Controllers;

use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    protected $validator;

    public function __construct()
    {
        $this->validator = app('Illuminate\Validation\Factory');
    }
}
