<?php

namespace App\Observers;

use App\Supplier;
use App\SupplierTransaction;

class SupplierObserver
{
    /**
     * Handle the supplier "created" event.
     *
     * @param  \App\Supplier  $supplier
     * @return void
     */

    public function created(Supplier $supplier)
    {
        if($supplier->balance > 0){
            $supplierTransaction = new SupplierTransaction();
            $supplierTransaction->suppliers_id = $supplier->id;
            $supplierTransaction->payment_type = 'Opening Balance';
            $supplierTransaction->transaction_type = 'OUT';
            $supplierTransaction->amount = $supplier->balance;
            $supplierTransaction->warehouses_id = $supplier->warehouses_id;
            $supplierTransaction->save();
        }
    }

    /**
     * Handle the supplier "updated" event.
     *
     * @param  \App\Supplier  $supplier
     * @return void
     */

    public function updated(Supplier $supplier)
    {
        if($supplier->balance > 0) {
            SupplierTransaction::updateOrCreate(
                ['suppliers_id' => $supplier->id, 'payment_type' => 'Opening Balance', 'transaction_type' => 'OUT'],
                [
                    'amount' => $supplier->balance,
                    'warehouses_id' => $supplier->warehouses_id
                ]
            );
        }else{
            SupplierTransaction::where('suppliers_id',  $supplier->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->forceDelete();
        }
    }

    /**
     * Handle the supplier "deleted" event.
     *
     * @param  \App\Supplier  $supplier
     * @return void
     */

    public function deleted(Supplier $supplier)
    {
        SupplierTransaction::where('suppliers_id',  $supplier->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->delete();
    }

    /**
     * Handle the supplier "restored" event.
     *
     * @param  \App\Supplier  $supplier
     * @return void
     */

    public function restored(Supplier $supplier)
    {
        SupplierTransaction::onlyTrashed()->where('suppliers_id',  $supplier->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->restore();
    }

    /**
     * Handle the supplier "force deleted" event.
     *
     * @param  \App\Supplier  $supplier
     * @return void
     */

    public function forceDeleted(Supplier $supplier)
    {
        SupplierTransaction::where('suppliers_id',  $supplier->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->forceDelete();
    }
}
