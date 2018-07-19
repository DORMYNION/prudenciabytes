<?php

namespace FI\Widgets\Dashboard\LoanSummary\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the view path.
        view()->addLocation(app_path('Widgets/Dashboard/LoanSummary/Views'));

        // Register the widget view composer.
        view()->composer('LoanSummaryWidget', 'FI\Widgets\Dashboard\LoanSummary\Composers\LoanSummaryWidgetComposer');

        // Register the setting view composer.
        view()->composer('LoanSummaryWidgetSettings', 'FI\Widgets\Dashboard\LoanSummary\Composers\LoanSummarySettingComposer');

        // Widgets don't have route files so we'll place this here.
        Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Widgets\Dashboard\LoanSummary\Controllers'], function () {
            Route::post('widgets/dashboard/loan_summary/render_partial', ['uses' => 'WidgetController@renderPartial', 'as' => 'widgets.dashboard.loanSummary.renderPartial']);
        });
    }

    public function register()
    {
        //
    }
}