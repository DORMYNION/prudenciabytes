<?php

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\Dashboard\Controllers'], function () {
    Route::get('/', 'DashboardController@index');
    Route::get('dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboard.index']);
});
