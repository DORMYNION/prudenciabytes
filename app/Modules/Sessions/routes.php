<?php

/**

 *
 
 


 
 *

 */

Route::group(['namespace' => 'FI\Modules\Sessions\Controllers', 'middleware' => 'web'], function () {
    Route::get('login', ['uses' => 'SessionController@login', 'as' => 'session.login']);
    Route::post('login', ['uses' => 'SessionController@attempt', 'as' => 'session.attempt']);
    Route::get('logout', ['uses' => 'SessionController@logout', 'as' => 'session.logout']);
});