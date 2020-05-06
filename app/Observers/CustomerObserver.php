<?php

namespace App\Observers;

use App\Customer;
use App\CustomerTransaction;

class CustomerObserver
{
    /**
     * Handle the customer "created" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        if($customer->balance > 0){
            $supplierTransaction = new CustomerTransaction();
            $supplierTransaction->customers_id = $customer->id;
            $supplierTransaction->payment_type = 'Opening Balance';
            $supplierTransaction->transaction_type = 'IN';
            $supplierTransaction->amount = $customer->balance;
            $supplierTransaction->warehouses_id = $customer->warehouses_id;
            $supplierTransaction->save();
        }
    }

    /**
     * Handle the customer "updated" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        if($customer->balance > 0) {
            CustomerTransaction::updateOrCreate(
                ['customers_id' => $customer->id, 'payment_type' => 'Opening Balance', 'transaction_type' => 'OUT'],
                [
                    'amount' => $customer->balance,
                    'warehouses_id' => $customer->warehouses_id
                ]
            );
        }else{
            CustomerTransaction::where('customers_id',  $customer->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->forceDelete();
        }
    }

    /**
     * Handle the customer "deleted" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        CustomerTransaction::where('customers_id',  $customer->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->delete();
    }

    /**
     * Handle the customer "restored" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        CustomerTransaction::onlyTrashed()->where('customers_id',  $customer->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->restore();
    }

    /**
     * Handle the customer "force deleted" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        CustomerTransaction::where('customers_id',  $customer->id)->where('payment_type', 'Opening Stock')->where('transaction_type', 'OUT')->forceDelete();
    }
}
