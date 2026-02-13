<?php

namespace Ameringkseh\StockManagement;

use Illuminate\Support\ServiceProvider;

class StockManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'stock-management');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
