<?php

/**

 *
 
 


 
 *

 */

Route::group(['prefix' => 'client_center', 'middleware' => 'web', 'namespace' => 'FI\Modules\ClientCenter\Controllers'], function () {
    Route::get('/', ['uses' => 'ClientCenterDashboardController@redirectToLogin']);
    Route::get('loan/{loanKey}', ['uses' => 'ClientCenterPublicLoanController@show', 'as' => 'clientCenter.public.loan.show']);
    Route::get('loan/{loanKey}/pdf', ['uses' => 'ClientCenterPublicLoanController@pdf', 'as' => 'clientCenter.public.loan.pdf']);
    Route::get('loan/{loanKey}/html', ['uses' => 'ClientCenterPublicLoanController@html', 'as' => 'clientCenter.public.loan.html']);
    Route::get('invest/{investKey}', ['uses' => 'ClientCenterPublicInvestController@show', 'as' => 'clientCenter.public.invest.show']);
    Route::get('invest/{investKey}/pdf', ['uses' => 'ClientCenterPublicInvestController@pdf', 'as' => 'clientCenter.public.invest.pdf']);
    Route::get('invest/{investKey}/html', ['uses' => 'ClientCenterPublicInvestController@html', 'as' => 'clientCenter.public.invest.html']);
    Route::get('invest/{investKey}/approve', ['uses' => 'ClientCenterPublicInvestController@approve', 'as' => 'clientCenter.public.invest.approve']);
    Route::get('invest/{investKey}/reject', ['uses' => 'ClientCenterPublicInvestController@reject', 'as' => 'clientCenter.public.invest.reject']);

    Route::group(['middleware' => 'auth.clientCenter'], function () {
        Route::get('dashboard', ['uses' => 'ClientCenterDashboardController@index', 'as' => 'clientCenter.dashboard']);
        Route::get('loans', ['uses' => 'ClientCenterLoanController@index', 'as' => 'clientCenter.loans']);
        Route::get('invests', ['uses' => 'ClientCenterInvestController@index', 'as' => 'clientCenter.invests']);
        Route::get('payments', ['uses' => 'ClientCenterPaymentController@index', 'as' => 'clientCenter.payments']);
    });
});