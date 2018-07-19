<?php


Route::group(['prefix' => 'api', 'middleware' => 'web', 'namespace' => 'FI\Modules\API\Controllers'], function () {
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::post('generate_keys', ['uses' => 'ApiKeyController@generateKeys', 'as' => 'api.generateKeys']);
    });

    Route::group(['middleware' => 'auth.api'], function () {
        Route::post('clients/list', ['uses' => 'ApiClientController@lists']);
        Route::post('clients/show', ['uses' => 'ApiClientController@show']);
        Route::post('clients/store', ['uses' => 'ApiClientController@store']);
        Route::post('clients/update', ['uses' => 'ApiClientController@update']);
        Route::post('clients/delete', ['uses' => 'ApiClientController@delete']);

        Route::post('invests/list', ['uses' => 'ApiInvestController@lists']);
        Route::post('invests/show', ['uses' => 'ApiInvestController@show']);
        Route::post('invests/store', ['uses' => 'ApiInvestController@store']);
        Route::post('invests/items/add', ['uses' => 'ApiInvestController@addItem']);
        Route::post('invests/delete', ['uses' => 'ApiInvestController@delete']);

        Route::post('loans/list', ['uses' => 'ApiLoanController@lists']);
        Route::post('loans/show', ['uses' => 'ApiLoanController@show']);
        Route::post('loans/store', ['uses' => 'ApiLoanController@store']);
        Route::post('loans/items/add', ['uses' => 'ApiLoanController@addItem']);
        Route::post('loans/delete', ['uses' => 'ApiLoanController@delete']);

        Route::post('payments/list', ['uses' => 'ApiPaymentController@lists']);
        Route::post('payments/show', ['uses' => 'ApiPaymentController@show']);
        Route::post('payments/store', ['uses' => 'ApiPaymentController@store']);
        Route::post('payments/items/add', ['uses' => 'ApiPaymentController@addItem']);
        Route::post('payments/delete', ['uses' => 'ApiPaymentController@delete']);
    });

});
