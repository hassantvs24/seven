<?php

namespace App\Providers;

use App\Observers\ProductObserver;
use App\Observers\StockAdjustmentItemObserver;
use App\Observers\StockAdjustmentObserver;
use App\Product;
use App\StockAdjustment;
use App\StockAdjustmentItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        StockAdjustmentItem::observe(StockAdjustmentItemObserver::class);
        StockAdjustment::observe(StockAdjustmentObserver::class);
        Product::observe(ProductObserver::class);
    }
}
