<?php

/**

 *
 
 


 
 *

 */

Route::group(['prefix' => 'mail_log', 'middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\MailQueue\Controllers'], function () {
    Route::get('/', ['uses' => 'MailLogController@index', 'as' => 'mailLog.index']);
    Route::post('content', ['uses' => 'MailLogController@content', 'as' => 'mailLog.content']);
    Route::get('{id}/delete', ['uses' => 'MailLogController@delete', 'as' => 'mailLog.delete']);
});