<?php

namespace App\Observers;

use App\StockAdjustment;
use App\StockAdjustmentItem;

class StockAdjustmentObserver
{
    /**
     * Handle the stock adjustment "created" event.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return void
     */
    public function created(StockAdjustment $stockAdjustment)
    {
        //
    }

    /**
     * Handle the stock adjustment "updated" event.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return void
     */
    public function updated(StockAdjustment $stockAdjustment)
    {
        //
    }

    /**
     * Handle the stock adjustment "deleted" event.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return void
     */
    public function deleted(StockAdjustment $stockAdjustment)
    {
        StockAdjustmentItem::where('stock_adjustments_id', $stockAdjustment->id)->delete();
    }

    /**
     * Handle the stock adjustment "restored" event.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return void
     */
    public function restored(StockAdjustment $stockAdjustment)
    {
        StockAdjustmentItem::onlyTrashed()->where('stock_adjustments_id', $stockAdjustment->id)->restore();
    }

    /**
     * Handle the stock adjustment "force deleted" event.
     *
     * @param  \App\StockAdjustment  $stockAdjustment
     * @return void
     */
    public function forceDeleted(StockAdjustment $stockAdjustment)
    {
        StockAdjustmentItem::where('stock_adjustments_id', $stockAdjustment->id)->forceDelete();
    }
}
