<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutDetails;
use App\Models\CheckoutItem;
use App\Models\City;
use App\Models\Commission;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerCategory;
use App\Models\SellerPayable;
use App\Models\ShippingFeeSetting;
use App\Models\SiteSetting;
use App\Models\WithdrawBalance;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    private function checkAndUpdateOrderStatus($order)
    {
        if ($order->order_status === 'delivered') {
            $deliveredTime = Carbon::parse($order->updated_at);
            $currentTime = Carbon::now();
            $hoursDifference = $currentTime->diffInHours($deliveredTime);

            if ($hoursDifference >= 72) {
                $order->order_status = 'completed';
                $order->save();

                $statusHistory = new OrderStatusHistory();
                $statusHistory->order_id = $order->id;
                $statusHistory->status = 'completed';
                $statusHistory->note = 'Order automatically marked as completed after 72 hours of delivery';
                $statusHistory->save();
            }
        }
        return $order;
    }

    public function SellerEarnings(Request $request) {
        $sellerId = $request->query('seller_id');
        $allWithdraw = WithdrawBalance::where('seller_id', $sellerId)->latest()->get();



        $orders = Order::with('checkout')->where('seller_id', $sellerId)
            ->latest();


        $orders = $orders->get()->map(function ($order) {
            $order->dubai_date_time = Carbon::parse($order->created_at)
                ->timezone('Asia/Dubai')
                ->format('d/m/Y , H:i');


            $seller = Seller::find(Auth::id());
            $sellerCurrency = Currency::where('id', $seller->currency_id)->latest()->first();



            $order->sellerCurrency = $sellerCurrency->title;
            return $order;
        });



        $ordersWithDetails = $orders->map(function ($order) {
            $orderDetails = OrderDetails::with([
                'product_details' => function ($query) {
                    $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                },
                'seller_details'
            ])->where('checkout_details', $order->order_details_checkout_id)
                ->where('seller_id', $order->seller_id)
                ->get();




            $order->total_invoice_value = 0;
            $order->total_tax = 0;
            $order->total_price_with_vat = 0;
            $order->total_price_without_vat = 0;
            $order->seller_total_shipping_fee = 0;
            $order->total_product_price = 0;
            $order->total_included_tax = 0;
            $order->total_excluded_tax = 0;

            $total_weight = 0;
            $seller_id = $order->seller_id;


            $checkoutModel = Checkout::find($order->order_details_checkout_id);
            $discountAsCurrency = $checkoutModel->discount;
            $order->discount = $discountAsCurrency;

            $subtotal_and_shipping_cost = $checkoutModel->subtotal + $checkoutModel->shipping_fee;
            foreach ($orderDetails as $detail) {

                if ($detail->tax_type === "Included") {
                    $order->total_included_tax += $detail->tax * $detail->qty;
                }



                if ($detail->tax_type === "Excluded") {
                    $order->total_excluded_tax += $detail->tax * $detail->qty;
                }


                $order->total_price_with_vat += $detail->final_price;
                $order->total_price_without_vat += $detail->product_price;








                $total_weight += $detail->wieght;
                $order->total_product_price += $detail->product_price;
                $order->total_invoice_value += $detail->final_price;
                $order->total_tax += $detail->tax * $detail->qty;
                // $order->commision_details = $sellerCategoriesWiseCommision;
                // $order->commisonFee = $order->total_price_without_vat * ($sellerCategoriesWiseCommision?->commission_rate / 100);
                // $order->promoterClubFee = $order->total_price_without_vat * ($sellerCategoriesWiseCommision?->promoter_club_fee / 100);
                $order->commisonFee += round($detail->commison, 2);
                $order->promoterClubFee += round($detail->promoter_fee, 2);

                $order->vatOnFee += round($detail->vat_on_fee, 2);



            }


            $order->seller_total_shipping_fee += $this->getShippingFee($seller_id, $total_weight);
            $order->total_invoice_value += ($order->seller_total_shipping_fee);
            $order->order_details = $orderDetails;
            $codCharges = Checkout::find($order->order_details_checkout_id);
            // $order->total_cod_fees = $this->percentConverter($order->total_invoice_value, $codCharges->cod_charge_percent);
            $order->total_cod_fees = round($this->percentConverter($order->invoice_value, $codCharges->cod_charge_percent), 2);
            $order->total_discount = round($this->discountConverter($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value), 2);

            //   $order->total_discount = round($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value));
            $discountForOrder = round(($order->total_invoice_value / $subtotal_and_shipping_cost) * $order->discount, 2);
            $order->total_invoice_value -= $discountForOrder;
            $order->final_invoice_value = ($order->invoice_value + $order->total_cod_fees + $order->seller_total_shipping_fee) - $order->total_discount;





            $sellerPayableDetails = SellerPayable::latest()->first();
            // return $sellerPayableDetails;
            $sellerPayablePrice = $order->total_price_without_vat;

            // return $order;

            if ($sellerPayableDetails->tax == 'added') {
                $sellerPayablePrice += $order->total_tax;
            } elseif ($sellerPayableDetails->tax == 'subtracted') {
                $sellerPayablePrice -= $order->total_tax;
            }




            if ($sellerPayableDetails->shipping_fee == 'added') {
                $sellerPayablePrice += $order->seller_total_shipping_fee;
            } elseif ($sellerPayableDetails->shipping_fee == 'subtracted') {
                $sellerPayablePrice -= $order->seller_total_shipping_fee;
            }


            if ($sellerPayableDetails->cod_charge == 'added') {
                $sellerPayablePrice += $order->total_cod_fees;
            } elseif ($sellerPayableDetails->cod_charge == 'subtracted') {
                $sellerPayablePrice -= $order->total_cod_fees;
            }




            if ($sellerPayableDetails->coupon_discount == 'added') {
                $sellerPayablePrice += $order->total_discount;
            } elseif ($sellerPayableDetails->coupon_discount == 'subtracted') {
                $sellerPayablePrice -= $order->total_discount;
            }




            if ($sellerPayableDetails->commision == 'added') {
                $sellerPayablePrice += $order->commisonFee;
            } elseif ($sellerPayableDetails->commision == 'subtracted') {
                $sellerPayablePrice -= $order->commisonFee;
            }





            if ($sellerPayableDetails->promoter_fee == 'added') {
                $sellerPayablePrice += $order->promoterClubFee;
            } elseif ($sellerPayableDetails->promoter_fee == 'subtracted') {
                $sellerPayablePrice -= $order->promoterClubFee;
            }





            if ($sellerPayableDetails->vat_on_fee == 'added') {
                $sellerPayablePrice += $order->vatOnFee;
            } elseif ($sellerPayableDetails->vat_on_fee == 'subtracted') {
                $sellerPayablePrice -= $order->vatOnFee;
            }



            $order->seller_payable_money = round($sellerPayablePrice, 2);


            $subcidy = 0;
            if ($order->payment_method == 'credit/debit card') {
                $subcidy = $order->total_discount;
            } elseif ($order->payment_method == 'paypal') {
                $subcidy = $order->total_discount;
            } elseif ($order->payment_method == 'cod') {
                $subcidy = $order->total_discount;
            } elseif ($order->payment_method == 'reward point') {
                $subcidy = $order->total_invoice_value + $order->total_discount;
            } elseif ($order->payment_method == 'gift') {
                $subcidy = $order->total_invoice_value + $order->total_discount;
            }
            $order->subcidy = round($subcidy, 2);


            $order->earnings = round(($order->final_invoice_value) - ($order->seller_payable_money), 2);

            $orderStatusHistory = OrderStatusHistory::where('order_id', $order->id)->latest()->get();
            $order->order_status_history = $orderStatusHistory;

            $order->order_status_history_data = view('orders.render_order_status_history_as_html', compact('order'))->render();
            $order->earnings_in_selected_currency = $this->convertCurrency($order->currency, $order->seller_payable_money, $order->sellerCurrency);
            return $order;
        });



        $total_earnings_in_selected_currency = 0.00;

        foreach ($ordersWithDetails as $order) {
            $converted_amount = (float) str_replace(',', '', $order->earnings_in_selected_currency['converted_amount']);
            $total_earnings_in_selected_currency += $converted_amount;
        }

        $formatted_total_current_currency_earnings = number_format($total_earnings_in_selected_currency, 2);



        // return $ordersWithDetails[0]['sellerCurrency'];

        $withdraw = WithdrawBalance::where('seller_id', $sellerId)->where('status', 'approve')->sum('amount');
        $withdraw_request = WithdrawBalance::where('seller_id', $sellerId)->where('status', 'pending')->sum('amount');
        $total_balance = $total_earnings_in_selected_currency - $withdraw ?? 0;

        $total_balance_data = number_format($total_balance,2);


        $seller_current_currency = Seller::find($sellerId);
        $seller_current_currency_name = Currency::find($seller_current_currency->currency_id)->title;

        return view('withdraw.seller_earning_view', compact  ('formatted_total_current_currency_earnings', 'withdraw', 'total_balance_data', 'withdraw_request', 'seller_current_currency_name','allWithdraw'));



    }

    public function DeleteSellerWithdraw(Request $request)
    {
        $withdraw_id = $request->query('wid');

        $withdraw = WithdrawBalance::find($withdraw_id);
        if ($withdraw) {
            $withdraw->delete();

            return back()->with('success', 'Withdraw delete Successfull.');
        } else {
            return back()->with('error', 'Withdraw not found.');
        }
    }


    public function PendingSellerWithdraw(Request $request)
    {
        $withdraw_id = $request->wid;
        $adminNote = $request->adminNote;

        $withdraw = WithdrawBalance::find($withdraw_id);
        if ($withdraw) {
            $withdraw->status = 'pending';
            $withdraw->admin_note = $adminNote;
            $withdraw->updated_at = Carbon::now();
            $withdraw->save();

            return back()->with('success', 'Withdraw mark as pending Successfull.');
        } else {
            return back()->with('error', 'Withdraw not found.');
        }
    }


    public function ApproveSellerWithdraw(Request $request)
    {
        $withdraw_id = $request->wid;
        $adminNote = $request->adminNote;

        $withdraw = WithdrawBalance::find($withdraw_id);
        if ($withdraw) {
            $withdraw->status = 'approve';
            $withdraw->admin_note = $adminNote;
            $withdraw->updated_at = Carbon::now();
            $withdraw->save();

            return back()->with('success', 'Withdrawal Successfull.');
        } else {
            return back()->with('error', 'Withdraw not found.');
        }
    }

    public function VendorWithdraw(Request $request)
    {
        $allWithdraw = WithdrawBalance::with('seller')->latest()->get();
        // return $allWithdraw;
        return view('withdraw.index', compact('allWithdraw'));
    }


    public function getShippingFee($seller_id, $shipping_weight)
    {
        // Fetch the latest shipping fee settings
        $shipping = ShippingFeeSetting::where('seller_id', $seller_id)->latest()->first();

        if (!$shipping) {
            return null;
        }

        if ($shipping_weight >= 1 && $shipping_weight <= 1000) {
            return $shipping->shipping_fee_1_to_1000;
        } elseif ($shipping_weight >= 1001 && $shipping_weight <= 3000) {
            return $shipping->shipping_fee_1001_to_3000;
        } elseif ($shipping_weight >= 3001 && $shipping_weight <= 5000) {
            return $shipping->shipping_fee_3001_to_5000;
        } elseif ($shipping_weight >= 5001 && $shipping_weight <= 10000) {
            return $shipping->shipping_fee_5001_to_10000;
        } elseif ($shipping_weight >= 10001 && $shipping_weight <= 15000) {
            return $shipping->shipping_fee_10001_to_15000;
        } elseif ($shipping_weight > 15000) {
            return $shipping->shipping_fee_above_15000;
        } else {
            // Handle invalid or out-of-range weights
            return null;
        }
    }


    public function percentConverter($total_amount, $percent)
    {
        return $total_amount * ($percent / 100);
    }
    public function discountConverter($total_subtotal, $discount, $own_subtotal)
    {
        return ($discount / $total_subtotal) * $own_subtotal;
    }


    public function yajra(Request $request)
    {
        if ($request->ajax()) {
            // Initialize date range
            $startDate = Carbon::parse("1000-01-01")->startOfDay();
            $endDate = Carbon::parse("3025-01-01")->endOfDay();

            if ($request->query('start_date') && $request->query('end_date')) {
                $start_date = $request->query('start_date');
                $end_date = $request->query('end_date');

                $startDate = Carbon::parse($start_date)->startOfDay();
                $endDate = Carbon::parse($end_date)->endOfDay();
            }

            // Initialize filters
            $countryId = $request->query('country_id') && is_numeric($request->query('country_id')) ? $request->query('country_id') : 0;
            $sellerId = $request->query('seller_id') && is_numeric($request->query('seller_id')) ? $request->query('seller_id') : 0;
            $orderedCurrency = $request->query('ordered_currency') == 'none' ? 0 : $request->query('ordered_currency');
            $orderStatus = $request->query('order_status') == 'none' ? 0 : $request->query('order_status');

            // Initialize the query builder with the basic conditions
            $orders = Order::with([
                'customer' => function ($query) {
                    $query->select('id', 'name', 'phone', 'email');
                },
                'checkout_items_details.product' => function ($query) {
                    $query->select('id', 'seller_id', 'name');
                },
                'checkout_items_details.product.seller' => function ($query) {
                    $query->select('id', 'name', 'shop_name', 'phone');
                },
                'country',
                'checkout_details',
                'seller' => function ($query) {
                    $query->select('id', 'name', 'shop_name');
                }
            ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest();

            // Apply filters
            if ($countryId != 0) {
                $orders = $orders->where('country_id', $countryId);
            }

            if ($sellerId != 0) {
                $orders = $orders->where('seller_id', $sellerId);
            }

            if ($orderedCurrency != 0) {
                $orders = $orders->where('currency', $orderedCurrency);
            }

            if ($orderStatus != 0) {
                $orders = $orders->where('order_status', $orderStatus);
            }

            $orders = $orders->get()->map(function ($order) {
                $order->dubai_date_time = Carbon::parse($order->created_at)
                    ->timezone('Asia/Dubai')
                    ->format('d/m/Y , H:i');
                return $order;
            });

            $orders = $orders->map(function ($order) {
                $sellerIds = collect($order->checkout_items_details)
                    ->pluck('product.seller_id')
                    ->unique()
                    ->filter();

                $sellerDetails = Seller::whereIn('id', $sellerIds)
                    ->get()
                    ->map(function ($seller) {
                        return [
                            'name' => $seller->name,
                            'phone' => $seller->phone,
                            'email' => $seller->email,
                            'account_status' => $seller->account_status,
                            'country_id' => $seller->country_id,
                            'account_type' => $seller->account_type,
                            'shop_name' => $seller->shop_name,
                        ];
                    });

                $order->sellers_details = $sellerDetails;
                $subtotal = $order->checkout?->subtotal ?? 0;
                $order->total_product_price = $subtotal;
                $order->total_price = $order->shipping_fee + $order->cod_charge + $subtotal;

                $order->order_status = $this->OrderStatus($order->order_details_checkout_id, $order->seller_id);

                $orderStatusHistory = OrderStatusHistory::where('order_id', $order->id)->latest()->get();
                $order->order_status_history = $orderStatusHistory;

                $order->order_status_history_data = view('orders.render_order_status_history_as_html', compact('order'))->render();

                return $order;
            });

            // Combine with the second controller logic
            $ordersWithDetails = $orders->map(function ($order) {
                $orderDetails = OrderDetails::with([
                    'product_details' => function ($query) {
                        $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                    },
                    'seller_details'
                ])->where('checkout_details', $order->order_details_checkout_id)
                    ->where('seller_id', $order->seller_id)
                    ->get();

                $order->total_invoice_value = 0;
                $order->total_tax = 0;
                $order->total_price_with_vat = 0;
                $order->total_price_without_vat = 0;
                $order->seller_total_shipping_fee = 0;
                $order->total_product_price = 0;
                $order->total_included_tax = 0;
                $order->total_excluded_tax = 0;

                $total_weight = 0;
                $seller_id = $order->seller_id;

                $checkoutModel = Checkout::find($order->order_details_checkout_id);
                $discountAsCurrency = $checkoutModel->discount;
                $order->discount = $discountAsCurrency;

                $subtotal_and_shipping_cost = $checkoutModel->subtotal + $checkoutModel->shipping_fee;
                foreach ($orderDetails as $detail) {
                    if ($detail->tax_type === "Included") {
                        $order->total_included_tax += $detail->tax * $detail->qty;
                    }

                    if ($detail->tax_type === "Excluded") {
                        $order->total_excluded_tax += $detail->tax * $detail->qty;
                    }

                    $order->total_price_with_vat += $detail->final_price;
                    $order->total_price_without_vat += $detail->product_price;

                    $total_weight += $detail->wieght;
                    $order->total_product_price += $detail->product_price;
                    $order->total_invoice_value += $detail->final_price;
                    $order->total_tax += $detail->tax * $detail->qty;
                    $order->commisonFee += number_format($detail->commison, 2, "," , "");
                    $order->promoterClubFee += number_format($detail->promoter_fee, 2, ',' , '');
                    $order->vatOnFee += number_format($detail->vat_on_fee, 2, ',' , '');
                }

                $order->seller_total_shipping_fee += $this->getShippingFee($seller_id, $total_weight);
                $order->total_invoice_value += ($order->seller_total_shipping_fee);
                $order->order_details = $orderDetails;
                $codCharges = Checkout::find($order->order_details_checkout_id);
                $order->total_cod_fees = number_format($this->percentConverter($order->total_invoice_value, $codCharges->cod_charge_percent), 2, ',' , '');

                $discountForOrder = number_format(($order->total_invoice_value / $subtotal_and_shipping_cost) * $order->discount, 2, ',' , '');
                $order->total_invoice_value -= $discountForOrder;

                $sellerPayableDetails = SellerPayable::latest()->first();
                $sellerPayablePrice = $order->total_price_without_vat;

                if ($sellerPayableDetails->tax == 'added') {
                    $sellerPayablePrice += $order->total_tax;
                } elseif ($sellerPayableDetails->tax == 'subtracted') {
                    $sellerPayablePrice -= $order->total_tax;
                }

                if ($sellerPayableDetails->shipping_fee == 'added') {
                    $sellerPayablePrice += $order->seller_total_shipping_fee;
                } elseif ($sellerPayableDetails->shipping_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->seller_total_shipping_fee;
                }

                if ($sellerPayableDetails->cod_charge == 'added') {
                    $sellerPayablePrice += $order->total_cod_fees;
                } elseif ($sellerPayableDetails->cod_charge == 'subtracted') {
                    $sellerPayablePrice -= $order->total_cod_fees;
                }

                if ($sellerPayableDetails->coupon_discount == 'added') {
                    $sellerPayablePrice += $order->discount;
                } elseif ($sellerPayableDetails->coupon_discount == 'subtracted') {
                    $sellerPayablePrice -= $order->discount;
                }

                if ($sellerPayableDetails->commision == 'added') {
                    $sellerPayablePrice += $order->commisonFee;
                } elseif ($sellerPayableDetails->commision == 'subtracted') {
                    $sellerPayablePrice -= $order->commisonFee;
                }

                if ($sellerPayableDetails->promoter_fee == 'added') {
                    $sellerPayablePrice += $order->promoterClubFee;
                } elseif ($sellerPayableDetails->promoter_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->promoterClubFee;
                }

                if ($sellerPayableDetails->vat_on_fee == 'added') {
                    $sellerPayablePrice += $order->vatOnFee;
                } elseif ($sellerPayableDetails->vat_on_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->vatOnFee;
                }

                $order->seller_payable = number_format($sellerPayablePrice, 2, ',', '');

                $subcidy = 0;
                if ($order->payment_method == 'credit/debit card') {
                    $subcidy = $order->discount;
                } elseif ($order->payment_method == 'paypal') {
                    $subcidy = $order->discount;
                } elseif ($order->payment_method == 'cod') {
                    $subcidy = $order->discount;
                } elseif ($order->payment_method == 'reward point') {
                    $subcidy = $order->total_invoice_value + $order->discount;
                } elseif ($order->payment_method == 'gift') {
                    $subcidy = $order->total_invoice_value + $order->discount;
                }
                $order->subcidy = number_format($subcidy, 2, ',' , '');

                $order->earnings = number_format(($order->total_invoice_value + $order->discount) - $order->seller_payable, 2, ',' , '');

                return $order;
            });

            return DataTables::of($ordersWithDetails)
                ->addColumn('formatted_date', function ($row) {
                    return $row->dubai_date_time;
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->checkout_details->billing_name ?? 'No Name';
                })
                ->addColumn('order_country_name', function ($row) {
                    return $row->country->country_name ?? 'No Name';
                })
                ->addColumn('billing_address', function ($row) {
                    return $row->checkout_details->billing_address ?? 'N/A';
                })
                ->addColumn('contract_number', function ($row) {
                    return $row->checkout_details->billing_mobile ?? 'N/A';
                })
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice_no ?? 'N/A';
                })
                ->addColumn('payment_method', function ($row) {
                    return $row->payment_method ?? 'N/A';
                })
                ->addColumn('payment_status', function ($row) {
                    return $row->payment_status ?? 'N/A';
                })
                ->addColumn('currency', function ($row) {
                    return $row->currency ?? 'N/A';
                })
                ->addColumn('invoice_value', function ($row) {
                    return $row->total_invoice_value . " " . $row->currency ?? 'N/A';
                })
                ->addColumn('tax', function ($row) {
                    return $row->total_tax ?? 'N/A';
                })
                ->addColumn('shipping_fee', function ($row) {
                    return $row->shipping_fee ?? 'N/A';
                })
                ->addColumn('total_product_price', function ($row) {
                    return $row->total_product_price ?? 'N/A';
                })
                ->addColumn('total_price', function ($row) {
                    return $row->checkout->total ?? 'N/A';
                })
                ->addColumn('seller_details', function ($row) {
                    return $row->seller->name ?? 'N/A';
                })
                ->addColumn('order_details_btn', function ($row) {
                    return "<a href='" . route('order.details', ['id' => $row->id, 'seller' => $row->seller_id]) . "' class='btn btn-sm btn-primary update-btn' style='margin-bottom:10px;' target='_blank'>Details</a>";
                })
                ->addColumn('order_status_dropdown', function ($row) {
                    return "
                        <div class='dropdown-container' style='position: relative; display: inline-block;'>
                            <button class='btn btn-sm btn-primary' style='display:flex; align-items:center;' onclick=\"openModal('Placed', " . $row->order_details_checkout_id . "," . $row->id . ", " . $row->seller_id . ", `" . addslashes($row->order_status_history_data) . "`) \">
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24'>
                                    <path fill='currentColor' d='M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5M12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3s3-1.34 3-3s-1.34-3-3-3'/>
                                </svg>
                                <span style='margin-left: 5px;'>" . $row->order_status . "</span>
                            </button>
                        </div>
                    ";
                })
                ->addColumn('checkout_items', function ($row) {
                    return collect($row->checkout_items_details)->map(function ($item) {
                        $productName = $item['product']['name'] ?? 'N/A';
                        $orderStatus = $item['order_status'] ?? 'N/A';
                        $sellerName = $item['product']['seller']['name'] ?? 'N/A';
                        $shopName = $item['product']['seller']['shop_name'] ?? 'N/A';
                        $phone = $item['product']['seller']['phone'] ?? 'N/A';

                        return "Name: {$productName}<br>Order Status: {$orderStatus}<br>Seller Name: {$sellerName}<br>Shop Name: {$shopName}<br>Phone Number: {$phone}";
                    })->join('<br><br>');
                })
                ->rawColumns(['checkout_items', 'order_details_btn', 'order_status_dropdown'])
                ->make(true);
        }

        return view('orders.yajra');
    }



    public function order_more_info(Request $request)
    {
        if ($request->ajax()) {

            $startDate = Carbon::parse("1000-01-01")->startOfDay();
            $endDate = Carbon::parse("3025-01-01")->endOfDay();

            if ($request->query('start_date') && $request->query('end_date')) {
                $start_date = $request->query('start_date');
                $end_date = $request->query('end_date');

                $startDate = Carbon::parse($start_date)->startOfDay();
                $endDate = Carbon::parse($end_date)->endOfDay();

                $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }


            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }



            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');

                }
            }


            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');

                }
            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }

            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }

            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');
                }
            }

            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');
                }
            }

            // Initialize the query builder with the basic conditions
            $orders = Order::with('checkout')->whereBetween('created_at', [$startDate, $endDate])
                ->latest();

            if ($countryId != 0) {
                $orders = $orders->where('country_id', $countryId);
            }

            if ($sellerId != 0) {
                $orders = $orders->where('seller_id', $sellerId);
            }

            if ($orderedCurrency != 0) {
                $orders = $orders->where('currency', $orderedCurrency);
            }

            if ($orderStatus != 0) {
                $orders = $orders->where('order_status', $orderStatus);
            }

            $orders = $orders->get()->map(function ($order) {
                // Check and update status if needed
                $order = $this->checkAndUpdateOrderStatus($order);

                $order->dubai_date_time = Carbon::parse($order->created_at)
                    ->timezone('Asia/Dubai')
                    ->format('d/m/Y , H:i');
                return $order;
            });

            // return $orders;




            $ordersWithDetails = $orders->map(function ($order) {
                $orderDetails = OrderDetails::with([
                    'product_details' => function ($query) {
                        $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                    },
                    'seller_details'
                ])->where('checkout_details', $order->order_details_checkout_id)
                    ->where('seller_id', $order->seller_id)
                    ->get();



                // return $orderDetails;
                $current_currency = Currency::where('title', $order->currency)->latest()->first();

                $order->total_invoice_value = 0;
                $order->total_tax = 0;
                $order->total_price_with_vat = 0;
                $order->total_price_without_vat = 0;
                $order->seller_total_shipping_fee = 0;
                $order->total_product_price = 0;
                $order->total_included_tax = 0;
                $order->total_excluded_tax = 0;

                $total_weight = 0;
                $seller_id = $order->seller_id;


                $checkoutModel = Checkout::find($order->order_details_checkout_id);
                $discountAsCurrency = $checkoutModel->discount;
                $order->discount = $discountAsCurrency;

                $subtotal_and_shipping_cost = $checkoutModel->subtotal + $checkoutModel->shipping_fee;
                foreach ($orderDetails as $detail) {

                    if ($detail->tax_type === "Included") {
                        $order->total_included_tax += $detail->tax * $detail->qty;
                    }



                    if ($detail->tax_type === "Excluded") {
                        $order->total_excluded_tax += $detail->tax * $detail->qty;
                    }


                    $order->total_price_with_vat += $detail->final_price;
                    $order->total_price_without_vat += $detail->product_price;








                    $total_weight += $detail->wieght;
                    $order->total_product_price += $detail->product_price;
                    $order->total_invoice_value += $detail->final_price;
                    $order->total_tax += $detail->tax * $detail->qty;
                    // $order->commision_details = $sellerCategoriesWiseCommision;
                    // $order->commisonFee = $order->total_price_without_vat * ($sellerCategoriesWiseCommision?->commission_rate / 100);
                    // $order->promoterClubFee = $order->total_price_without_vat * ($sellerCategoriesWiseCommision?->promoter_club_fee / 100);
                    $order->commisonFee += round($detail->commison, 2);
                    $order->promoterClubFee += round($detail->promoter_fee, 2);

                    $order->vatOnFee += round($detail->vat_on_fee, 2);



                }


                $order->seller_total_shipping_fee += $this->getShippingFee($seller_id, $total_weight);
                $order->total_invoice_value += ($order->seller_total_shipping_fee);
                $order->order_details = $orderDetails;
                $codCharges = Checkout::find($order->order_details_checkout_id);
                // $order->total_cod_fees = $this->percentConverter($order->total_invoice_value, $codCharges->cod_charge_percent);
                $order->total_cod_fees = round($this->percentConverter($order->invoice_value, $codCharges->cod_charge_percent), 2);
                $order->total_discount = round($this->discountConverter($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value), 2);

                //   $order->total_discount = round($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value));
                $discountForOrder = number_format(($order->total_invoice_value / $subtotal_and_shipping_cost) * $order->discount, 2);
                $order->total_invoice_value -= $discountForOrder;
                $order->final_invoice_value = number_format((($order->invoice_value + $order->total_cod_fees + $order->seller_total_shipping_fee) - $order->total_discount) * $current_currency->usd_conversion_rate, 2, '.', '');





                $sellerPayableDetails = SellerPayable::latest()->first();
                // return $sellerPayableDetails;
                $sellerPayablePrice = $order->total_price_without_vat;

                // return $order;

                if ($sellerPayableDetails->tax == 'added') {
                    $sellerPayablePrice += $order->total_tax;
                } elseif ($sellerPayableDetails->tax == 'subtracted') {
                    $sellerPayablePrice -= $order->total_tax;
                }




                if ($sellerPayableDetails->shipping_fee == 'added') {
                    $sellerPayablePrice += $order->seller_total_shipping_fee;
                } elseif ($sellerPayableDetails->shipping_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->seller_total_shipping_fee;
                }


                if ($sellerPayableDetails->cod_charge == 'added') {
                    $sellerPayablePrice += $order->total_cod_fees;
                } elseif ($sellerPayableDetails->cod_charge == 'subtracted') {
                    $sellerPayablePrice -= $order->total_cod_fees;
                }




                if ($sellerPayableDetails->coupon_discount == 'added') {
                    $sellerPayablePrice += $order->total_discount;
                } elseif ($sellerPayableDetails->coupon_discount == 'subtracted') {
                    $sellerPayablePrice -= $order->total_discount;
                }




                if ($sellerPayableDetails->commision == 'added') {
                    $sellerPayablePrice += $order->commisonFee;
                } elseif ($sellerPayableDetails->commision == 'subtracted') {
                    $sellerPayablePrice -= $order->commisonFee;
                }





                if ($sellerPayableDetails->promoter_fee == 'added') {
                    $sellerPayablePrice += $order->promoterClubFee;
                } elseif ($sellerPayableDetails->promoter_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->promoterClubFee;
                }





                if ($sellerPayableDetails->vat_on_fee == 'added') {
                    $sellerPayablePrice += $order->vatOnFee;
                } elseif ($sellerPayableDetails->vat_on_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->vatOnFee;
                }



                $order->seller_payable_money = round($sellerPayablePrice, 2);


                $subcidy = 0;
                if ($order->payment_method == 'credit/debit card') {
                    $subcidy = $order->total_discount;
                } elseif ($order->payment_method == 'paypal') {
                    $subcidy = $order->total_discount;
                } elseif ($order->payment_method == 'cod') {
                    $subcidy = $order->total_discount;
                } elseif ($order->payment_method == 'reward point') {
                    $subcidy = $order->total_invoice_value + $order->total_discount;
                } elseif ($order->payment_method == 'gift') {
                    $subcidy = $order->total_invoice_value + $order->total_discount;
                }
                $order->subcidy = round($subcidy, 2);


                $order->earnings = round(($order->final_invoice_value) - ($order->seller_payable_money), 2);

                $current_currency = SiteSetting::latest()->first();
                $earningData = $this->convertCurrency($order->currency, $order->earnings, $current_currency->value);

                $order->admin_currency_earning = $earningData['converted_amount'];
                $order->admin_currency_earning_currency = $current_currency->value;
                return $order;
            });








            return DataTables::of($ordersWithDetails)
                ->addColumn('invoice_value', function ($row) {
                    return $row->total_invoice_value . " " . $row->currency ?? 'N/A';
                })
                ->addColumn('tax', function ($row) {
                    return $row->total_tax ?? 'N/A';
                })
                ->addColumn('shipping_fee', function ($row) {
                    return $row->shipping_fee ?? 'N/A';
                })
                ->addColumn('checkout_cod_charge', function ($row) {
                    return $row->total_cod_fees ?? 'N/A';
                })
                ->addColumn('product_only_price', function ($row) {
                    return $row->total_price_with_vat . " " . $row->currency ?? 'N/A';
                })
                ->addColumn('vat_on_fee', function ($row) {
                    return round($row->vatOnFee, 2);
                })
                ->addColumn('seller_payable_amnt', function ($row) {
                    return round($row->seller_payable_money, 2);
                })
                ->addColumn('order_details_btn', function ($row) {
                    return "<a href='" . route('order.details', ['id' => $row->id]) . "' class='btn btn-sm btn-primary update-btn' style='margin-bottom:10px;' target='_blank'>Details</a>";
                })

                ->addColumn('checkout_items', function ($row) {
                    return collect($row->checkout_items_details)->map(function ($item) {
                        $productName = $item['product']['name'] ?? 'N/A';
                        $orderStatus = $item['order_status'] ?? 'N/A';
                        $sellerName = $item['product']['seller']['name'] ?? 'N/A';
                        $shopName = $item['product']['seller']['shop_name'] ?? 'N/A';
                        $phone = $item['product']['seller']['phone'] ?? 'N/A';

                        return "Name: {$productName}<br>Order Status: {$orderStatus}<br>Seller Name: {$sellerName}<br>Shop Name: {$shopName}<br>Phone Number: {$phone}";
                    })->join('<br><br>');
                })
                ->rawColumns(['checkout_items', 'order_details_btn'])
                ->make(true);



        } else {
            $startDate = Carbon::parse("1000-01-01")->startOfDay();
            $endDate = Carbon::parse("3025-01-01")->endOfDay();

            if ($request->query('start_date') && $request->query('end_date')) {
                $start_date = $request->query('start_date');
                $end_date = $request->query('end_date');

                $startDate = Carbon::parse($start_date)->startOfDay();
                $endDate = Carbon::parse($end_date)->endOfDay();

                $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }


            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }



            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');

                }
            }


            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');

                }
            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }

            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }

            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');
                }
            }

            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');
                }
            }

            // Initialize the query builder with the basic conditions
            $orders = Order::with('checkout')->whereBetween('created_at', [$startDate, $endDate])
                ->latest();

            if ($countryId != 0) {
                $orders = $orders->where('country_id', $countryId);
            }

            if ($sellerId != 0) {
                $orders = $orders->where('seller_id', $sellerId);
            }

            if ($orderedCurrency != 0) {
                $orders = $orders->where('currency', $orderedCurrency);
            }

            if ($orderStatus != 0) {
                $orders = $orders->where('order_status', $orderStatus);
            }

            $orders = $orders->get()->map(function ($order) {
                // Check and update status if needed
                $order = $this->checkAndUpdateOrderStatus($order);

                $order->dubai_date_time = Carbon::parse($order->created_at)
                    ->timezone('Asia/Dubai')
                    ->format('d/m/Y , H:i');
                return $order;
            });

            // return $orders;




            $ordersWithDetails = $orders->map(function ($order) {
                $orderDetails = OrderDetails::with([
                    'product_details' => function ($query) {
                        $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                    },
                    'seller_details'
                ])->where('checkout_details', $order->order_details_checkout_id)
                    ->where('seller_id', $order->seller_id)
                    ->get();



                // return $orderDetails;

                $order->total_invoice_value = 0;
                $order->total_tax = 0;
                $order->total_price_with_vat = 0;
                $order->total_price_without_vat = 0;
                $order->seller_total_shipping_fee = 0;
                $order->total_product_price = 0;
                $order->total_included_tax = 0;
                $order->total_excluded_tax = 0;

                $total_weight = 0;
                $seller_id = $order->seller_id;


                $checkoutModel = Checkout::find($order->order_details_checkout_id);
                $discountAsCurrency = $checkoutModel->discount;
                $order->discount = $discountAsCurrency;

                $subtotal_and_shipping_cost = $checkoutModel->subtotal + $checkoutModel->shipping_fee;
                foreach ($orderDetails as $detail) {

                    if ($detail->tax_type === "Included") {
                        $order->total_included_tax += $detail->tax * $detail->qty;
                    }



                    if ($detail->tax_type === "Excluded") {
                        $order->total_excluded_tax += $detail->tax * $detail->qty;
                    }


                    $order->total_price_with_vat += $detail->final_price;
                    $order->total_price_without_vat += $detail->product_price;








                    $total_weight += $detail->wieght;
                    $order->total_product_price += $detail->product_price;
                    $order->total_invoice_value += $detail->final_price;
                    $order->total_tax += $detail->tax * $detail->qty;
                    // $order->commision_details = $sellerCategoriesWiseCommision;
                    // $order->commisonFee = $order->total_price_without_vat * ($sellerCategoriesWiseCommision?->commission_rate / 100);
                    // $order->promoterClubFee = $order->total_price_without_vat * ($sellerCategoriesWiseCommision?->promoter_club_fee / 100);
                    $order->commisonFee += round($detail->commison, 2);
                    $order->promoterClubFee += round($detail->promoter_fee, 2);

                    $order->vatOnFee += round($detail->vat_on_fee, 2);



                }


                $order->seller_total_shipping_fee += $this->getShippingFee($seller_id, $total_weight);
                $order->total_invoice_value += ($order->seller_total_shipping_fee);
                $order->order_details = $orderDetails;
                $codCharges = Checkout::find($order->order_details_checkout_id);
                // $order->total_cod_fees = $this->percentConverter($order->total_invoice_value, $codCharges->cod_charge_percent);
                $order->total_cod_fees = round($this->percentConverter($order->invoice_value, $codCharges->cod_charge_percent), 2);
                $order->total_discount = round($this->discountConverter($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value), 2);

                //   $order->total_discount = round($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value));
                $discountForOrder = round(($order->total_invoice_value / $subtotal_and_shipping_cost) * $order->discount, 2);
                $order->total_invoice_value -= $discountForOrder;
                $order->final_invoice_value = ($order->invoice_value + $order->total_cod_fees + $order->seller_total_shipping_fee) - $order->total_discount;

                // return $orders




                $sellerPayableDetails = SellerPayable::latest()->first();
                // return $sellerPayableDetails;
                $sellerPayablePrice = $order->total_price_without_vat;

                // return $order;

                if ($sellerPayableDetails->tax == 'added') {
                    $sellerPayablePrice += $order->total_tax;
                } elseif ($sellerPayableDetails->tax == 'subtracted') {
                    $sellerPayablePrice -= $order->total_tax;
                }




                if ($sellerPayableDetails->shipping_fee == 'added') {
                    $sellerPayablePrice += $order->seller_total_shipping_fee;
                } elseif ($sellerPayableDetails->shipping_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->seller_total_shipping_fee;
                }


                if ($sellerPayableDetails->cod_charge == 'added') {
                    $sellerPayablePrice += $order->total_cod_fees;
                } elseif ($sellerPayableDetails->cod_charge == 'subtracted') {
                    $sellerPayablePrice -= $order->total_cod_fees;
                }




                if ($sellerPayableDetails->coupon_discount == 'added') {
                    $sellerPayablePrice += $order->total_discount;
                } elseif ($sellerPayableDetails->coupon_discount == 'subtracted') {
                    $sellerPayablePrice -= $order->total_discount;
                }




                if ($sellerPayableDetails->commision == 'added') {
                    $sellerPayablePrice += $order->commisonFee;
                } elseif ($sellerPayableDetails->commision == 'subtracted') {
                    $sellerPayablePrice -= $order->commisonFee;
                }





                if ($sellerPayableDetails->promoter_fee == 'added') {
                    $sellerPayablePrice += $order->promoterClubFee;
                } elseif ($sellerPayableDetails->promoter_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->promoterClubFee;
                }





                if ($sellerPayableDetails->vat_on_fee == 'added') {
                    $sellerPayablePrice += $order->vatOnFee;
                } elseif ($sellerPayableDetails->vat_on_fee == 'subtracted') {
                    $sellerPayablePrice -= $order->vatOnFee;
                }

                // return $order;


                $order->seller_payable_money = round($sellerPayablePrice, 2);


                $subcidy = 0;
                if ($order->payment_method == 'credit/debit card') {
                    $subcidy = $order->total_discount;
                } elseif ($order->payment_method == 'paypal') {
                    $subcidy = $order->total_discount;
                } elseif ($order->payment_method == 'cod') {
                    $subcidy = $order->total_discount;
                } elseif ($order->payment_method == 'reward point') {
                    $subcidy = $order->total_invoice_value + $order->total_discount;
                } elseif ($order->payment_method == 'gift') {
                    $subcidy = $order->total_invoice_value + $order->total_discount;
                }
                $order->subcidy = round($subcidy, 2);


                $order->earnings = round(($order->total_invoice_value + $order->discount) - $order->seller_payable, 2);


                return $order;
            });






            // return $ordersWithDetails;










            $allCountries = Country::all();
            $allSellers = Seller::all();
            $allCurrency = Currency::all();
            return view('orders.more_order_info', compact('allCountries', 'allSellers', 'allCurrency'));

            // return view('orders.index');
        }
    }








    public function order_details(Request $request)
    {


        if ($request->ajax()) {
            $orderId = $request->query('id');
            $sellerId = $request->query('seller');
            $order = Order::where('id', $orderId)->where('seller_id', $sellerId)->latest()->first();

            // Check and update status if needed
            if ($order) {
                $order = $this->checkAndUpdateOrderStatus($order);
            }

            $orderDetails = OrderDetails::with([
                'product_details' => function ($query) {
                    $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                },
                'seller_details'
            ])->where('checkout_details', $order->order_details_checkout_id)
                ->where('seller_id', $order->seller_id)
                ->get();





            return DataTables::of($orderDetails)
                ->addColumn('item_name', function ($row) {
                    $itemName = $row->product_details->name;

                    // Check if size exists and append
                    if ($row->size) {
                        $itemName .= "<br> Size: " . $row->size;
                    }

                    // Check if color exists and append
                    if ($row->color) {
                        $itemName .= "<br> Color: " . $row->color;
                    }

                    return $itemName;
                })

                ->addColumn('qty', function ($row) {
                    return $row->qty;
                })
                ->addColumn('price', function ($row) {
                    return $row->price * $row->qty;
                })
                ->addColumn('final_price', function ($row) {
                    return $row->final_price;
                })
                ->addColumn('tax', function ($row) {
                    return $row->tax * $row->qty;
                })
                ->addColumn('product_price', function ($row) {
                    return $row->product_price;
                })
                ->addColumn('commisonFee', function ($row) {
                    return $row->commison;
                })
                ->addColumn('promoter_fee', function ($row) {
                    return $row->promoter_fee;
                })
                ->addColumn('vat_on_fee', function ($row) {
                    return $row->vat_on_fee;
                })
                ->rawColumns(['item_name'])
                ->make(true);



        } else {

            $allCountries = Country::all();

            if ($request->query('id') && $request->query('seller')) {
                $orderId = $request->query('id');
                $sellerId = $request->query('seller');
                $order = Order::where('id', $orderId)->where('seller_id', $sellerId)->latest()->first();

                // Check and update status if needed
                if ($order) {
                    $order = $this->checkAndUpdateOrderStatus($order);
                }

                $orderDetails = OrderDetails::with([
                    'product_details' => function ($query) {
                        $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                    },
                    'seller_details'
                ])->where('checkout_details', $order->order_details_checkout_id)
                    ->where('seller_id', $order->seller_id)
                    ->get();



                // return $orderDetails;



                return view('orders.details', compact('allCountries'));
            }

            abort(404);
        }


    }



    public function OrderUpdate(Request $request)
    {
        $cartData = json_decode($request->cart_data, true);
        // return $cartData;

        $checkoutItem = ['subtotal' => 0, 'shipping_fee' => 0, 'total' => 0];
        foreach ($cartData as $item) {
            $checkoutItem['subtotal'] += $item['price'] + $item['tax'];
            $checkoutItem['shipping_fee'] += $item['shipping_charge'];
            $checkoutItem['total'] += $item['totalPriceWithShipping'];
        }

        $checkout = Checkout::find($request->checkout_id);
        $checkout->subtotal = $checkoutItem['subtotal'];
        $checkout->shipping_fee = $checkoutItem['shipping_fee'];
        $checkout->total = $checkoutItem['total'];

        $checkout->save();


        $billingDetails = CheckoutDetails::where('checkout_id', $request->checkout_id)->first();
        $billingDetails->billing_name = $request->billing_name;
        $billingDetails->billing_address = $request->billing_address;
        $billingDetails->billing_country = $request->country_id;
        $billingDetails->billing_city = $request->city_id;
        $billingDetails->save();

        $checkout_items = CheckoutItem::where('checkout_id', $request->checkout_id)->get();

        // return $cartData;

        foreach ($checkout_items as $item) {
            $item->delete();
        }

        foreach ($cartData as $item) {
            $checkoutItem = new CheckoutItem();
            $checkoutItem->checkout_id = $request->checkout_id;
            $checkoutItem->product_id = $item['id'];
            $checkoutItem->variant_id = $item['variant_id'];
            $checkoutItem->quantity = $item['quantity'];
            $checkoutItem->weight = $item['weight'] * $item['quantity'];
            $checkoutItem->final_price = $item['totalPriceWithShipping'] - $item['shipping_charge'];
            $checkoutItem->type = $item['type'];
            $checkoutItem->seller_id = $item['seller_id'];

            $checkoutItem->save();
        }



        return back()->with('success', 'Order updated successfully');


    }





    public function edit_view(Request $request)
    {
        $order = Order::with('checkout_items_details.product', 'checkout_details.country', 'checkout_details.city', 'checkout_details')->findOrFail($request->query('order_id'));

        $products = Product::with('seller', 'image')->latest()->get()->map(function ($product) {
            $image_link = env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/images/' . $product->image->image_1;
            $product->image_link = $image_link;


            $tax = 0;
            if ($product->tax_option == "Included") {
                $tax = 0;
            } else {
                $tax = $product->tax_pct ? ($product->selling_price * $product->tax_pct) / 100 : 0;
            }



            $tax_as_price = $tax;
            $product->tax_as_price = $tax_as_price;

            return $product;
        });

        $checkoutItems = $order->checkout_items_details->map(function ($item) {
            $product = $item->product;

            $tax = 0;
            if ($product->tax_option == "Included") {
                $tax = 0;
            } else {
                $tax = $product->tax_pct ? ($product->selling_price * $product->tax_pct) / 100 : 0;
            }



            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => Str::limit($product->product_description, 10, '...'),
                'price' => $product->selling_price,
                'tax' => $tax,
                'weight' => $product->weight,
                'shipping_charge' => $product->weight,
                'img' => $product->brand_image
                    ? env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/images/' . $product->image->image_1
                    : "https://via.placeholder.com/100",
                'quantity' => $item->quantity,
                'seller_id' => $item->seller_id,
                'variant_id' => $item->variant_id,
                'type' => $item->type,
            ];
        });

        $countries = Country::all();
        $cities = City::where('country_id', $order->checkout_details->billing_country)->get();

        $shippingFees = ShippingFeeSetting::latest()->first();

        // return $checkoutItems;

        return view('orders.edit', compact('products', 'order', 'checkoutItems', 'countries', 'cities', 'shippingFees'));
    }



    public function GetCities(Request $request)
    {
        $cities = City::where('country_id', $request->query('country_id'))->get();
        return response()->json($cities);
    }



    public function OrderStatus($checkoutId, $seller_id)
    {
        $checkoutItems = CheckoutItem::where('checkout_id', $checkoutId)->where('seller_id', $seller_id)->get();

        $orderStatus = 'No status';

        foreach ($checkoutItems as $item) {
            $status = strtolower($item->order_status);
            if ($status == 'placed') {
                $orderStatus = 'Placed';
            }
            if ($status == 'cancel by seller') {
                $orderStatus = 'Cancel by seller';
            }
            if ($status == 'cancel by customer') {
                $orderStatus = 'Cancel by customer';
            }
            if ($status == 'confirmed') {
                $orderStatus = 'Confirmed';
            }
            if ($status == 'dispatched') {
                $orderStatus = 'Dispatched';
            }
            if ($status == 'delivered') {
                $orderStatus = 'Delivered';
            }
            if ($status == 'returned') {
                $orderStatus = 'Returned';
            }
        }

        return $orderStatus;
    }


    public function convertCurrency($currentCurrency, $amount, $targetCurrency)
    {
        // Find exchange rates for both currencies
        $currentCurrencyRate = Currency::where('title', $currentCurrency)->first();
        $targetCurrencyRate = Currency::where('title', $targetCurrency)->first();

        if (!$currentCurrencyRate || !$targetCurrencyRate) {
            return response()->json(['error' => 'Invalid currency provided'], 400);
        }

        // Convert amount to USD first, then to target currency
        $amountInUSD = $amount / $currentCurrencyRate->usd_conversion_rate;
        $convertedAmount = $amountInUSD * $targetCurrencyRate->usd_conversion_rate;

        return [
            'original_currency' => $currentCurrency,
            'original_amount' => number_format($amount, 2, '.', ','),
            'converted_currency' => $targetCurrency,
            // 'converted_amount' => round($convertedAmount,2)
            'converted_amount' => number_format($convertedAmount, 2, '.', '')

        ];
    }


    public function view(Request $request)
    {
        // return view('orders.index');

        if ($request->ajax()) {

            $startDate = Carbon::parse("1000-01-01")->startOfDay();
            $endDate = Carbon::parse("3025-01-01")->endOfDay();

            if ($request->query('start_date') && $request->query('end_date')) {
                $start_date = $request->query('start_date');
                $end_date = $request->query('end_date');

                $startDate = Carbon::parse($start_date)->startOfDay();
                $endDate = Carbon::parse($end_date)->endOfDay();

                $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }


            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }



            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');

                }
            }


            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');

                }
            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }

            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }

            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');
                }
            }

            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');
                }
            }

            // Initialize the query builder with the basic conditions
            $orders = Order::with([
                'customer' => function ($query) {
                    $query->select('id', 'name', 'phone', 'email');
                },
                'checkout_items_details.product' => function ($query) {
                    $query->select('id', 'seller_id', 'name');
                },
                'checkout_items_details.product.seller' => function ($query) {
                    $query->select('id', 'name', 'shop_name', 'phone');
                },
                'country',
                'checkout_details',
                'seller' => function ($query) {
                    $query->select('id', 'name', 'shop_name');
                }
            ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest();

            if ($countryId != 0) {
                $orders = $orders->where('country_id', $countryId);
            }

            if ($sellerId != 0) {
                $orders = $orders->where('seller_id', $sellerId);
            }

            if ($orderedCurrency != 0) {
                $orders = $orders->where('currency', $orderedCurrency);
            }

            if ($orderStatus != 0) {
                $orders = $orders->where('order_status', $orderStatus);
            }

            $orders = $orders->get()->map(function ($order) {
                // Check and update status if needed
                $order = $this->checkAndUpdateOrderStatus($order);

                $order->dubai_date_time = Carbon::parse($order->created_at)
                    ->timezone('Asia/Dubai')
                    ->format('d/m/Y , H:i');
                return $order;
            });



            $orders = $orders->map(function ($order) {
                $sellerIds = collect($order->checkout_items_details)
                    ->pluck('product.seller_id')
                    ->unique()
                    ->filter();

                $sellerDetails = Seller::whereIn('id', $sellerIds)
                    ->get()
                    ->map(function ($seller) {
                        return [
                            'name' => $seller->name,
                            'phone' => $seller->phone,
                            'email' => $seller->email,
                            'account_status' => $seller->account_status,
                            'country_id' => $seller->country_id,
                            'account_type' => $seller->account_type,
                            'shop_name' => $seller->shop_name,
                        ];
                    });

                $order->sellers_details = $sellerDetails;
                $subtotal = $order->checkout?->subtotal ?? 0;
                $order->total_product_price = $subtotal;
                $order->total_price = $order->shipping_fee + $order->cod_charge + $subtotal;

                $order->order_status = $this->OrderStatus($order->order_details_checkout_id, $order->seller_id);

                $orderStatusHistory = OrderStatusHistory::where('order_id', $order->id)->latest()->get();
                $order->order_status_history = $orderStatusHistory;

                $order->order_status_history_data = view('orders.render_order_status_history_as_html', compact('order'))->render();
                $current_currency = SiteSetting::latest()->first();
                $earningData = $this->convertCurrency($current_currency->value, 4540, "AED");

                $order->admin_currency_earning = $earningData;
                return $order;
            });

            return DataTables::of($orders)
                ->addColumn('formatted_date', function ($row) {
                    return $row->dubai_date_time;
                })

                ->addColumn('customer_name', function ($row) {
                    return $row->checkout_details->billing_name ?? 'No Name';
                })

                ->addColumn('order_country_name', function ($row) {
                    return $row->country->country_name ?? 'No Name';
                })
                ->addColumn('billing_address', function ($row) {
                    return $row->checkout_details->billing_address ?? 'N/A';
                })
                ->addColumn('contract_number', function ($row) {
                    return $row->checkout_details->billing_mobile ?? 'N/A';
                })
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice_no ?? 'N/A';
                })
                ->addColumn('payment_method', function ($row) {
                    return $row->payment_method ?? 'N/A';
                })
                ->addColumn('payment_status', function ($row) {
                    return $row->payment_status ?? 'N/A';
                })
                ->addColumn('currency', function ($row) {
                    return $row->currency ?? 'N/A';
                })
                ->addColumn('invoice_value', function ($row) {
                    return $row->total_price ?? 'N/A';
                })
                ->addColumn('tax', function ($row) {
                    return $row->tax ?? 'N/A';
                })
                ->addColumn('shipping_fee', function ($row) {
                    return $row->shipping_fee ?? 'N/A';
                })

                ->addColumn('total_product_price', function ($row) {
                    return $row->total_product_price ?? 'N/A';
                })
                ->addColumn('total_price', function ($row) {
                    return $row->checkout->total ?? 'N/A';
                })
                ->addColumn('seller_details', function ($row) {
                    return $row->seller->name ?? 'N/A';
                })
                ->addColumn('order_details_btn', function ($row) {
                    return "<a href='" . route('order.details', ['id' => $row->id, 'seller' => $row->seller_id]) . "' class='btn btn-sm btn-primary update-btn' style='margin-bottom:10px;' target='_blank'>Details</a>";
                })
                ->addColumn('order_status_dropdown', function ($row) {
                    return "
                        <div class='dropdown-container' style='position: relative; display: inline-block;'>
                            <button class='btn btn-sm btn-primary' style='display:flex; align-items:center;' onclick=\"openModal('Placed', " . $row->order_details_checkout_id . "," . $row->id . ", " . $row->seller_id . ", `" . addslashes($row->order_status_history_data) . "`) \">
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24'>
                                    <path fill='currentColor' d='M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5M12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3s3-1.34 3-3s-1.34-3-3-3'/>
                                </svg>
                                <span style='margin-left: 5px;'>" . $row->order_status . "</span>
                            </button>
                        </div>
                    ";
                })


                ->addColumn('checkout_items', function ($row) {
                    return collect($row->checkout_items_details)->map(function ($item) {
                        $productName = $item['product']['name'] ?? 'N/A';
                        $orderStatus = $item['order_status'] ?? 'N/A';
                        $sellerName = $item['product']['seller']['name'] ?? 'N/A';
                        $shopName = $item['product']['seller']['shop_name'] ?? 'N/A';
                        $phone = $item['product']['seller']['phone'] ?? 'N/A';

                        return "Name: {$productName}<br>Order Status: {$orderStatus}<br>Seller Name: {$sellerName}<br>Shop Name: {$shopName}<br>Phone Number: {$phone}";
                    })->join('<br><br>');
                })
                ->rawColumns(['checkout_items', 'order_details_btn', 'order_status_dropdown'])
                ->make(true);



        } else {

            $startDate = Carbon::parse("1000-01-01")->startOfDay();
            $endDate = Carbon::parse("3025-01-01")->endOfDay();

            if ($request->query('start_date') && $request->query('end_date')) {
                $start_date = $request->query('start_date');
                $end_date = $request->query('end_date');

                $startDate = Carbon::parse($start_date)->startOfDay();
                $endDate = Carbon::parse($end_date)->endOfDay();

                $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }


            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }



            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');

                }
            }


            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');

                }
            }



            $countryId = 0;
            if ($request->query('country_id') && is_numeric($request->query('country_id'))) {
                $countryId = $request->query('country_id');
            }

            $sellerId = 0;
            if ($request->query('seller_id') && is_numeric($request->query('seller_id'))) {
                $sellerId = $request->query('seller_id');
            }

            $orderedCurrency = 0;
            if ($request->query('ordered_currency')) {
                if ($request->query('ordered_currency') == 'none') {
                    $orderedCurrency = 0;
                } else {
                    $orderedCurrency = $request->query('ordered_currency');
                }
            }

            $orderStatus = 0;
            if ($request->query('order_status')) {
                if ($request->query('order_status') == 'none') {
                    $orderStatus = 0;
                } else {
                    $orderStatus = $request->query('order_status');
                }
            }

            // Initialize the query builder with the basic conditions
            $orders = Order::with([
                'customer' => function ($query) {
                    $query->select('id', 'name', 'phone', 'email');
                },
                'checkout_items_details.product' => function ($query) {
                    $query->select('id', 'seller_id', 'name');
                },
                'checkout_items_details.product.seller' => function ($query) {
                    $query->select('id', 'name', 'shop_name', 'phone');
                },
                'country',
                'checkout_details',
                'seller' => function ($query) {
                    $query->select('id', 'name', 'shop_name');
                }
            ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest();

            // Dynamically add where conditions based on the values of the variables
            if ($countryId != 0) {
                $orders = $orders->where('country_id', $countryId);
            }

            if ($sellerId != 0) {
                $orders = $orders->where('seller_id', $sellerId);
            }

            if ($orderedCurrency != 0) {
                $orders = $orders->where('currency', $orderedCurrency);
            }

            if ($orderStatus != 0) {
                $orders = $orders->where('order_status', $orderStatus);
            }

            $orders = $orders->get()->map(function ($order) {
                // Check and update status if needed
                $order = $this->checkAndUpdateOrderStatus($order);

                $order->dubai_date_time = Carbon::parse($order->created_at)
                    ->timezone('Asia/Dubai')
                    ->format('d/m/Y , H:i');
                return $order;
            });

            // return $orders;




            $orders = $orders->map(function ($order) {
                $sellerIds = collect($order->checkout_items_details)
                    ->pluck('product.seller_id')
                    ->unique()
                    ->filter();

                $sellerDetails = Seller::whereIn('id', $sellerIds)
                    ->get()
                    ->map(function ($seller) {
                        return [
                            'name' => $seller->name,
                            'phone' => $seller->phone,
                            'email' => $seller->email,
                            'account_status' => $seller->account_status,
                            'country_id' => $seller->country_id,
                            'account_type' => $seller->account_type,
                            'shop_name' => $seller->shop_name,
                        ];
                    });

                $order->sellers_details = $sellerDetails;
                $subtotal = $order->checkout?->subtotal ?? 0;
                $order->total_product_price = $subtotal;
                $order->total_price = $order->shipping_fee + $order->cod_charge + $subtotal;

                $order->order_status = $this->OrderStatus($order->order_details_checkout_id, $order->seller_id);

                $orderStatusHistory = OrderStatusHistory::where('order_id', $order->id)->get();
                $order->order_status_history = $orderStatusHistory;

                // Render the order status history as HTML
                // $order->order_status_history_data = view('orders.render_order_status_history_as_html', compact('order'))->render();
                $order->order_status_history_data = view('orders.render_order_status_history_as_html', compact('order'))->render();

                return $order;
            });

            // return $orders[0]->order_status_history_data;




            $allCountries = Country::all();
            $allSellers = Seller::all();
            $allCurrency = Currency::all();

            return view('orders.index', compact('allCountries', 'allSellers', 'allCurrency'));
        }




    }

    public function DeleteOrder(Request $request)
    {
        if ($request->query('delete_order')) {
            $order = Order::find($request->query('delete_order'));
            if ($order) {
                $order->delete();
                return redirect()->back()->with('success', 'Order deleted successfully');
            } else {
                return redirect()->back()->with('error', 'Order not found');
            }
        } else {
            abort(404);
        }
    }

}
