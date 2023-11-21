<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderHistory;

class OrderObserve
{
    public function created(Order $order): void
    {
        $history = new OrderHistory();
        $history->order_id = $order->id;
        $history->state = $order->state;
        $history->save();
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $history = new OrderHistory();
        $history->order_id = $order->id;
        $history->state = $order->state;
        $history->save();
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
