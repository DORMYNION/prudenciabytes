<?php

/**

 *
 
 


 
 *

 */

Route::group(['prefix' => 'notes', 'middleware' => ['web', 'auth'], 'namespace' => 'FI\Modules\Notes\Controllers'], function () {
    Route::post('create', ['uses' => 'NoteController@create', 'as' => 'notes.create']);
    Route::post('delete', ['uses' => 'NoteController@delete', 'as' => 'notes.delete']);
});