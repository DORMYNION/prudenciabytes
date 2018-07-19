<?php

/**

 *
 
 


 
 *

 */

Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\RecurringLoans\Controllers'], function () {
    Route::group(['prefix' => 'recurring_loans'], function () {
        Route::get('/', ['uses' => 'RecurringLoanController@index', 'as' => 'recurringLoans.index']);
        Route::get('create', ['uses' => 'RecurringLoanCreateController@create', 'as' => 'recurringLoans.create']);
        Route::post('create', ['uses' => 'RecurringLoanCreateController@store', 'as' => 'recurringLoans.store']);
        Route::get('{id}/edit', ['uses' => 'RecurringLoanEditController@edit', 'as' => 'recurringLoans.edit']);
        Route::post('{id}/edit', ['uses' => 'RecurringLoanEditController@update', 'as' => 'recurringLoans.update']);
        Route::get('{id}/delete', ['uses' => 'RecurringLoanController@delete', 'as' => 'recurringLoans.delete']);

        Route::get('{id}/edit/refresh', ['uses' => 'RecurringLoanEditController@refreshEdit', 'as' => 'recurringLoanEdit.refreshEdit']);
        Route::post('edit/refresh_to', ['uses' => 'RecurringLoanEditController@refreshTo', 'as' => 'recurringLoanEdit.refreshTo']);
        Route::post('edit/refresh_from', ['uses' => 'RecurringLoanEditController@refreshFrom', 'as' => 'recurringLoanEdit.refreshFrom']);
        Route::post('edit/refresh_totals', ['uses' => 'RecurringLoanEditController@refreshTotals', 'as' => 'recurringLoanEdit.refreshTotals']);
        Route::post('edit/update_client', ['uses' => 'RecurringLoanEditController@updateClient', 'as' => 'recurringLoanEdit.updateClient']);
        Route::post('edit/update_company_profile', ['uses' => 'RecurringLoanEditController@updateCompanyProfile', 'as' => 'recurringLoanEdit.updateCompanyProfile']);
        Route::post('recalculate', ['uses' => 'RecurringLoanRecalculateController@recalculate', 'as' => 'recurringLoans.recalculate']);
    });

    Route::group(['prefix' => 'recurring_loan_copy'], function () {
        Route::post('create', ['uses' => 'RecurringLoanCopyController@create', 'as' => 'recurringLoanCopy.create']);
        Route::post('store', ['uses' => 'RecurringLoanCopyController@store', 'as' => 'recurringLoanCopy.store']);
    });

    Route::group(['prefix' => 'recurring_loan_item'], function () {
        Route::post('delete', ['uses' => 'RecurringLoanItemController@delete', 'as' => 'recurringLoanItem.delete']);
    });
});