<?php



Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\Loans\Controllers'], function () {
    Route::group(['prefix' => 'loans'], function () {
        Route::get('/', ['uses' => 'LoanController@index', 'as' => 'loans.index']);
        Route::get('form', ['uses' => 'LoanController@form', 'as' => 'loans.form']);
        Route::get('create', ['uses' => 'LoanCreateController@create', 'as' => 'loans.create']);
        Route::post('create', ['uses' => 'LoanCreateController@store', 'as' => 'loans.store']);
        Route::get('{id}/edit', ['uses' => 'LoanEditController@edit', 'as' => 'loans.edit']);
        Route::post('{id}/edit', ['uses' => 'LoanEditController@update', 'as' => 'loans.update']);
        Route::get('{id}/delete', ['uses' => 'LoanController@delete', 'as' => 'loans.delete']);
        Route::get('{id}/pdf', ['uses' => 'LoanController@pdf', 'as' => 'loans.pdf']);

        Route::get('{id}/edit/refresh', ['uses' => 'LoanEditController@refreshEdit', 'as' => 'loanEdit.refreshEdit']);
        Route::post('edit/refresh_to', ['uses' => 'LoanEditController@refreshTo', 'as' => 'loanEdit.refreshTo']);
        Route::post('edit/refresh_from', ['uses' => 'LoanEditController@refreshFrom', 'as' => 'loanEdit.refreshFrom']);
        Route::post('edit/refresh_totals', ['uses' => 'LoanEditController@refreshTotals', 'as' => 'loanEdit.refreshTotals']);
        Route::post('edit/update_client', ['uses' => 'LoanEditController@updateClient', 'as' => 'loanEdit.updateClient']);
        Route::post('edit/update_company_profile', ['uses' => 'LoanEditController@updateCompanyProfile', 'as' => 'loanEdit.updateCompanyProfile']);
        Route::post('recalculate', ['uses' => 'LoanRecalculateController@recalculate', 'as' => 'loans.recalculate']);
        Route::post('bulk/delete', ['uses' => 'LoanController@bulkDelete', 'as' => 'loans.bulk.delete']);
        Route::post('bulk/status', ['uses' => 'LoanController@bulkStatus', 'as' => 'loans.bulk.status']);
    });

    Route::group(['prefix' => 'loan_copy'], function () {
        Route::post('create', ['uses' => 'LoanCopyController@create', 'as' => 'loanCopy.create']);
        Route::post('store', ['uses' => 'LoanCopyController@store', 'as' => 'loanCopy.store']);
    });

    Route::group(['prefix' => 'loan_mail'], function () {
        Route::post('create', ['uses' => 'LoanMailController@create', 'as' => 'loanMail.create']);
        Route::post('store', ['uses' => 'LoanMailController@store', 'as' => 'loanMail.store']);
    });

    Route::group(['prefix' => 'loan_item'], function () {
        Route::post('delete', ['uses' => 'LoanItemController@delete', 'as' => 'loanItem.delete']);
    });
});
