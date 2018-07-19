<?php

namespace FI\Modules\Dashboard\Controllers;

use FI\Http\Controllers\Controller;
use FI\Support\DashboardWidgets;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index')
            ->with('widgets', DashboardWidgets::listsByOrder());
    }
}
