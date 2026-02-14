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
        
        $this->publishes([
            __DIR__.'/../../config/stock-management.php' => config_path('stock-management.php'),
        ], 'stock-management-config');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/stock-management.php', 'stock-management'
        );
    }
}
