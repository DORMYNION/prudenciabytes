<?php



Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Modules\Invests\Controllers'], function () {
    Route::group(['prefix' => 'invests'], function () {
        Route::get('/', ['uses' => 'InvestController@index', 'as' => 'invests.index']);
        Route::get('create', ['uses' => 'InvestCreateController@create', 'as' => 'invests.create']);
        Route::post('create', ['uses' => 'InvestCreateController@store', 'as' => 'invests.store']);
        Route::get('{id}/edit', ['uses' => 'InvestEditController@edit', 'as' => 'invests.edit']);
        Route::post('{id}/edit', ['uses' => 'InvestEditController@update', 'as' => 'invests.update']);
        Route::get('{id}/delete', ['uses' => 'InvestController@delete', 'as' => 'invests.delete']);
        Route::get('{id}/pdf', ['uses' => 'InvestController@pdf', 'as' => 'invests.pdf']);

        Route::get('{id}/edit/refresh', ['uses' => 'InvestEditController@refreshEdit', 'as' => 'investEdit.refreshEdit']);
        Route::post('edit/refresh_to', ['uses' => 'InvestEditController@refreshTo', 'as' => 'investEdit.refreshTo']);
        Route::post('edit/refresh_from', ['uses' => 'InvestEditController@refreshFrom', 'as' => 'investEdit.refreshFrom']);
        Route::post('edit/refresh_totals', ['uses' => 'InvestEditController@refreshTotals', 'as' => 'investEdit.refreshTotals']);
        Route::post('edit/update_client', ['uses' => 'InvestEditController@updateClient', 'as' => 'investEdit.updateClient']);
        Route::post('edit/update_company_profile', ['uses' => 'InvestEditController@updateCompanyProfile', 'as' => 'investEdit.updateCompanyProfile']);
        Route::post('recalculate', ['uses' => 'InvestRecalculateController@recalculate', 'as' => 'invests.recalculate']);
        Route::post('bulk/delete', ['uses' => 'InvestController@bulkDelete', 'as' => 'invests.bulk.delete']);
        Route::post('bulk/status', ['uses' => 'InvestController@bulkStatus', 'as' => 'invests.bulk.status']);
    });

    Route::group(['prefix' => 'invest_copy'], function () {
        Route::post('create', ['uses' => 'InvestCopyController@create', 'as' => 'investCopy.create']);
        Route::post('store', ['uses' => 'InvestCopyController@store', 'as' => 'investCopy.store']);
    });

    Route::group(['prefix' => 'invest_to_loan'], function () {
        Route::post('create', ['uses' => 'InvestToLoanController@create', 'as' => 'investToLoan.create']);
        Route::post('store', ['uses' => 'InvestToLoanController@store', 'as' => 'investToLoan.store']);
    });

    Route::group(['prefix' => 'invest_mail'], function () {
        Route::post('create', ['uses' => 'InvestMailController@create', 'as' => 'investMail.create']);
        Route::post('store', ['uses' => 'InvestMailController@store', 'as' => 'investMail.store']);
    });

    Route::group(['prefix' => 'invest_item'], function () {
        Route::post('delete', ['uses' => 'InvestItemController@delete', 'as' => 'investItem.delete']);
    });
});
