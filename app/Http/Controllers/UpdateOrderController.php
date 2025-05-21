<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutItem;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Auth;
use Illuminate\Http\Request;

class UpdateOrderController extends Controller
{
    public function updateOrder(Request $request)
    {
        $checkoutId = $request->checkout_id;
        $sellerId = $request->seller_id;
        $note = $request->note;
        $status = $request->status;
        $orderId = $request->order_id;

        $checkout = CheckoutItem::where('checkout_id', $checkoutId)->where('seller_id', $sellerId)->get();

        foreach ($checkout as $item) {
            $item->order_status = $status;
            $item->save();
        }


        OrderStatusHistory::create([
            'checkout_id' => $checkoutId,
            'order_id' => $orderId,
            'seller_id' => $sellerId,
            'note' => $note,
            'status_name' => $status,
            //    'added_by' => Auth::user()->id,
        ]);

        $orderStatus = Order::find($orderId);
        $orderStatus->order_status = $status;
        $orderStatus->save();


        return redirect()->back();
    }
}
