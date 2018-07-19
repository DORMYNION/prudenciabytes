<?php

/**

 *
 
 


 
 *

 */

Route::group(['middleware' => ['web', 'auth.admin'], 'prefix' => 'export', 'namespace' => 'FI\Modules\Exports\Controllers'], function () {
    Route::get('/', ['uses' => 'ExportController@index', 'as' => 'export.index']);
    Route::post('{export}', ['uses' => 'ExportController@export', 'as' => 'export.export']);
});