<?php

namespace FI\Widgets\Dashboard\InvestSummary\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the view path.
        view()->addLocation(app_path('Widgets/Dashboard/InvestSummary/Views'));

        // Register the widget view composer.
        view()->composer('InvestSummaryWidget', 'FI\Widgets\Dashboard\InvestSummary\Composers\InvestSummaryWidgetComposer');

        // Register the setting view composer.
        view()->composer('InvestSummaryWidgetSettings', 'FI\Widgets\Dashboard\InvestSummary\Composers\InvestSummarySettingComposer');

        // Widgets don't have route files so we'll place this here.
        Route::group(['middleware' => ['web', 'auth.admin'], 'namespace' => 'FI\Widgets\Dashboard\InvestSummary\Controllers'], function () {
            Route::post('widgets/dashboard/invest_summary/render_partial', ['uses' => 'WidgetController@renderPartial', 'as' => 'widgets.dashboard.investSummary.renderPartial']);
        });
    }

    public function register()
    {
        //
    }
}