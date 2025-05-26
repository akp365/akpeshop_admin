<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Order;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RewardPointController extends Controller
{
    private function getRedeemStatus($order_status, $payment_method, $product_type = null)
    {
        $status = strtolower($order_status);

        if ($payment_method == 'reward point') {
            // For spending reward points
            if (in_array($status, ['placed', 'confirmed', 'dispatched', 'delivered', 'completed'])) {
                return "Redeemed";
            } elseif (in_array($status, ['cancel by seller', 'cancel by customer', 'returned'])) {
                return "Reversed";
            }
        } elseif ($product_type == 'Reward Point Offer') {
            // For earning reward points
            if (in_array($status, ['placed', 'confirmed', 'dispatched', 'delivered'])) {
                return "Processing";
            } elseif ($status == 'completed') {
                return "Approved";
            } elseif (in_array($status, ['cancel by seller', 'cancel by customer', 'returned'])) {
                return "Declined";
            }
        }

        return null; // Return null for orders that don't affect reward points
    }

    public function ShowRewardPointHistory(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with(['customer', 'checkout', 'checkout_items_details.product', 'checkout_details'])
                ->where(function ($query) {
                    $query->where('payment_method', 'reward point')
                        ->orWhereHas('checkout_items_details.product', function ($q) {
                            $q->where('product_type', 'Reward Point Offer');
                        });
                });

            // Apply date filters if provided
            if ($request->fromDate && $request->toDate) {
                $fromDate = Carbon::parse($request->fromDate)->startOfDay();
                $toDate = Carbon::parse($request->toDate)->endOfDay();
                $query->whereBetween('updated_at', [$fromDate, $toDate]);
            }

            // Apply status filter if provided
            if ($request->status) {
                $status = strtolower($request->status);
                switch ($status) {
                    case 'approved':
                        $query->where('order_status', 'completed')
                            ->whereHas('checkout_items_details.product', function ($q) {
                                $q->where('product_type', 'Reward Point Offer');
                            });
                        break;
                    case 'redeemed':
                        $query->where('payment_method', 'reward point')
                            ->whereIn('order_status', ['placed', 'confirmed', 'dispatched', 'delivered', 'completed']);
                        break;
                    case 'reversed':
                        $query->where('payment_method', 'reward point')
                            ->whereIn('order_status', ['cancel by seller', 'cancel by customer', 'returned']);
                        break;
                    case 'processing':
                        $query->whereHas('checkout_items_details.product', function ($q) {
                            $q->where('product_type', 'Reward Point Offer');
                        })->whereIn('order_status', ['placed', 'confirmed', 'dispatched', 'delivered']);
                        break;
                    case 'declined':
                        $query->whereHas('checkout_items_details.product', function ($q) {
                            $q->where('product_type', 'Reward Point Offer');
                        })->whereIn('order_status', ['cancel by seller', 'cancel by customer', 'returned']);
                        break;
                }
            }

            // Get default currency
            $default_currency_select = SiteSetting::latest()->first();
            $default_currency = Currency::where('title', $default_currency_select->value)->latest()->first();

            $rewardPointOrder = $query->latest()->get();

            $formattedOrders = $rewardPointOrder->map(function ($order) use ($default_currency_select, $default_currency) {
                $totalFinalPrice = $order->checkout->total;
                $totalQuantity = $order->checkout_items_details->sum('quantity');
                $totalRewardPoint = 0;

                // Calculate reward points only for Reward Point Offer products
                foreach ($order->checkout_items_details as $item) {
                    if ($item->product->product_type == 'Reward Point Offer') {
                        $totalRewardPoint += ($item->product->selling_price * ($item->product->reward_point / 100)) * $item->quantity;
                    }
                }

                $ordered_currency = Currency::where('title', $order->currency)->latest()->first();

                // Determine if any product in the order is a Reward Point Offer
                $hasRewardPointOffer = $order->checkout_items_details->contains(function ($item) {
                    return $item->product->product_type == 'Reward Point Offer';
                });

                $status = $this->getRedeemStatus(
                    $order->order_status,
                    $order->checkout_details->payment_method,
                    $hasRewardPointOffer ? 'Reward Point Offer' : null
                );

                // Skip orders that don't affect reward points
                if (!$status) {
                    return null;
                }

                $earn_redeem = $order->checkout_details->payment_method == 'reward point' ?
                    "-" . $totalFinalPrice * $ordered_currency->usd_conversion_rate :
                    '+' . $totalRewardPoint * $ordered_currency->usd_conversion_rate;

                $rp_convert_to_default_currency = ($earn_redeem / $ordered_currency->usd_conversion_rate) * $default_currency->usd_conversion_rate;

                return [
                    'id' => $order->id,
                    'data_time' => Carbon::parse($order->updated_at)->format('Y-m-d H:i:s'),
                    'invoice_no' => $order->invoice_no,
                    'user_name' => $order->customer->name ?? 'N/A',
                    'user_email' => $order->customer->email ?? 'N/A',
                    'earn_redeem' => $earn_redeem . " $ordered_currency->title",
                    'convert_rp_to_default_currency' => $rp_convert_to_default_currency . " $default_currency->title",
                    'status' => $status,
                    'default_currency' => $default_currency_select->value,
                    'quantity' => $totalQuantity,
                    'final_price' => $totalFinalPrice,
                    'total_reward_points' => $totalRewardPoint,
                    'ordered_at' => $order->updated_at,
                    'payment_method' => $order->checkout_details->payment_method,
                    'original_status' => $order->order_status,
                ];
            })->filter(); // Remove null entries

            // Calculate totals for filtered data
            $totalApproved = 0;
            $totalRedeemed = 0;
            $totalReversed = 0;
            $totalProcessing = 0;
            $totalDeclined = 0;

            foreach ($formattedOrders as $order) {
                $amount = floatval(
                    str_replace(
                        [$order['default_currency'], '+', '-', ' '],
                        '',
                        $order['convert_rp_to_default_currency']
                    )
                );

                switch ($order['status']) {
                    case 'Approved':
                        $totalApproved += $amount;
                        break;
                    case 'Redeemed':
                        $totalRedeemed += abs($amount); // Use absolute value for redeemed
                        break;
                    case 'Reversed':
                        $totalReversed += abs($amount); // Use absolute value for reversed
                        break;
                    case 'Processing':
                        $totalProcessing += $amount;
                        break;
                    case 'Declined':
                        $totalDeclined += $amount;
                        break;
                }
            }

            $total_balance_default_currency = $totalApproved - $totalRedeemed + $totalReversed;

            // Add summary data to the response
            $summaryData = [
                'total_approved' => 'RP ' . number_format($totalApproved, 2) . ' ' . $default_currency_select->value,
                'total_redeemed' => 'RP ' . number_format($totalRedeemed, 2) . ' ' . $default_currency_select->value,
                'total_reversed' => 'RP ' . number_format($totalReversed, 2) . ' ' . $default_currency_select->value,
                'total_processing' => 'RP ' . number_format($totalProcessing, 2) . ' ' . $default_currency_select->value,
                'total_declined' => 'RP ' . number_format($totalDeclined, 2) . ' ' . $default_currency_select->value,
                'total_balance' => 'RP ' . number_format($total_balance_default_currency, 2) . ' ' . $default_currency_select->value,
            ];

            return DataTables::of($formattedOrders)
                ->with('summary', $summaryData)
                ->make(true);
        }

        // For non-AJAX requests...
        $default_currency_select = SiteSetting::latest()->first();
        $default_currency = Currency::where('title', $default_currency_select->value)->latest()->first();

        // Initialize empty data array with zero values
        $data = [
            'total_approved' => 'RP 0.00 ' . $default_currency_select->value,
            'total_redeemed' => 'RP 0.00 ' . $default_currency_select->value,
            'total_reversed' => 'RP 0.00 ' . $default_currency_select->value,
            'total_processing' => 'RP 0.00 ' . $default_currency_select->value,
            'total_declined' => 'RP 0.00 ' . $default_currency_select->value,
            'total_balance' => 'RP 0.00 ' . $default_currency_select->value,
        ];

        return view('reward-points.index', compact('data'));
    }
}
