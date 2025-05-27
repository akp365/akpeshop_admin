<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\CheckoutItem;
use App\Models\OrderStatusHistory;
use Carbon\Carbon;

class UpdateDeliveredOrders extends Command
{
    protected $signature = 'orders:update-delivered';
    protected $description = 'Update delivered orders to completed after 72 hours';

    public function handle()
    {
        // Get all orders that are delivered and older than 72 hours
        $cutoffTime = Carbon::now()->subHours(72);

        $orders = Order::where('order_status', 'Delivered')
            ->whereHas('order_status_history', function($query) use ($cutoffTime) {
                $query->where('status_name', 'Delivered')
                    ->where('created_at', '<=', $cutoffTime);
            })
            ->get();

        foreach ($orders as $order) {
            // Update order status
            $order->order_status = 'Completed';
            $order->save();

            // Update checkout items status
            CheckoutItem::where('checkout_id', $order->order_details_checkout_id)
                ->where('seller_id', $order->seller_id)
                ->update(['order_status' => 'Completed']);

            // Create status history entry
            OrderStatusHistory::create([
                'checkout_id' => $order->order_details_checkout_id,
                'order_id' => $order->id,
                'seller_id' => $order->seller_id,
                'status_name' => 'Completed',
                'note' => 'Automatically marked as completed after 72 hours of delivery'
            ]);
        }

        $this->info('Successfully updated ' . $orders->count() . ' delivered orders to completed.');
    }
}
