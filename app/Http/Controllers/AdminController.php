<?php

namespace App\Http\Controllers;

use App\Models\AssignTicket;
use App\Models\Checkout;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\OpenTicket;
use App\Models\OpenTicketMessage;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerPayable;
use App\Models\ShippingFeeSetting;
use App\Models\SiteSetting;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function GiftBalance() {
        return view('gift_balance');
    }



    public function AssignTicket(Request $request) {
        $ticketId = $request->ticket_id;
        $vendorId = $request->vendor_id;
        $status = $request->status;

        $ticket = OpenTicket::find($ticketId);
        if ($ticket) {
            $ticket->update([
                'status' => $status
            ]);
        }

        AssignTicket::create([
            'ticket_id' => $ticketId,
            'vendor' => $vendorId,
        ]);

        return back();
    }


    public function SendTicketMessage(Request $request) {
        $ticket_id = $request->ticket_id;
        $message = $request->message;

        OpenTicketMessage::create([
            'ticket_id'=> $ticket_id,
            'admin_id'=> Auth::id(),
            'message'=> $message,
        ]);

        return back()->with('success','Message send success');

    }


    public function OpenTicketView(Request $request) {
        $unique_id = $request->ticket_unique_id;
        $openTicketId = OpenTicket::with('assign_vendor')->where("unique_id", $unique_id)->first();

        $user = Auth::user();

        $openTicket = OpenTicketMessage::where('ticket_id', $openTicketId->id)->get();


        // return $openTicketId;
        $allVendor = Seller::latest()->get();

        return view('open_ticket_view', compact('openTicket', 'allVendor', 'openTicketId'));

    }

    public function OpenTicketList(Request $request) {
        $tickets = OpenTicket::latest()->get();
        return view('open_ticket_list', compact('tickets'));
    }


    public function get_total_sells(Request $request)
    {
        $allCurrencies = Currency::all()->pluck('title')->toArray();
        // return $allCurrencies;
        // Get vendor's current currency


        $adminSelectedCurrency = SiteSetting::latest()->first();
        $adminSelectedCurrency = $adminSelectedCurrency->value;
        $allCurrenciesFindByname = Currency::where('title' , $adminSelectedCurrency)->latest()->first();
        // return $allCurrenciesFindByname;
        $vendorCurrentCurrency = Auth::user()->currency_id;
        // $rateExchange = Currency::find($vendorCurrentCurrency);
        $usd_convert_rate = $allCurrenciesFindByname->usd_conversion_rate;
        $admin_current_curreny_name = $allCurrenciesFindByname->title;
        // Get orders grouped by currency
        $total_orders = Order::get()->groupBy('currency');

        // return $total_orders;

        $currency_summary = [];
        $totalCurrencyAmnt = 0;

        foreach ($total_orders as $currency => $orders) {
            $total_amount = $orders->sum('invoice_value');

            // Find exchange rate for the current currency
            $currency_rate = Currency::where('title', $currency)->first();

            if ($currency_rate) {
                $currency_to_usd = $currency_rate->usd_conversion_rate;
                $converted_amount = ($total_amount / $currency_to_usd) * $usd_convert_rate;
            } else {
                $converted_amount = 0; // Handle missing exchange rates
            }

            $currency_summary[$currency] = [
                'total_amount' => $total_amount,
                'total_selected_currency_amnt' => number_format(round($converted_amount, 2), 2, '.', ',')
            ];

            // Accumulate total converted amount as a float
            $totalCurrencyAmnt += round($converted_amount, 2);
        }

        // Format the final total amount
        $totalCurrencyAmnt = number_format($totalCurrencyAmnt, 2, '.', ',');

        return [
            'currency_summary' => $currency_summary,
            'currency_name' => $admin_current_curreny_name,  // Name of the currency (e.g., AED, USD)
            'total_selected_currency_amnt' => $totalCurrencyAmnt
        ];



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



    public function index(Request $request)
    {
        $startDate = Carbon::parse("1000-01-01")->startOfDay();
        $endDate = Carbon::parse("3025-01-01")->endOfDay();

        if ($request->query('start_date') && $request->query('end_date')) {
            $start_date = $request->query('start_date');
            $end_date = $request->query('end_date');

            $startDate = Carbon::parse($start_date)->startOfDay();
            $endDate = Carbon::parse($end_date)->endOfDay();
        }

        // Initialize the query builder with the basic conditions
        $orders = Order::with('checkout')->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get()
            ->map(function ($order) {
                $order->dubai_date_time = Carbon::parse($order->created_at)
                    ->timezone('Asia/Dubai')
                    ->format('d/m/Y , H:i');
                return $order;
            });

        // Group the orders by month name
        $ordersGroupedByMonth = $orders->groupBy(function ($order) {
            return $order->created_at->format('F'); // Get the month name (January, February, etc.)
        });

        // Retrieve the order details with the necessary information and add total earnings for each month
        $ordersWithDetails = $ordersGroupedByMonth->map(function ($ordersInMonth, $month) {
            $totalEarningsForMonth = 0;

            // Process each order in the month
            $ordersInMonthWithDetails = $ordersInMonth->map(function ($order) use (&$totalEarningsForMonth) {
                $orderDetails = OrderDetails::with([
                    'product_details' => function ($query) {
                        $query->select('id', 'seller_id', 'product_code', 'status', 'name', 'category_id', 'sub_category_id');
                    },
                    'seller_details'
                ])->where('checkout_details', $order->order_details_checkout_id)
                    ->where('seller_id', $order->seller_id)
                    ->get();

                // Initialize totals for the order
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
                    $order->commisonFee += round($detail->commison, 2);
                    $order->promoterClubFee += round($detail->promoter_fee, 2);
                    $order->vatOnFee += round($detail->vat_on_fee, 2);
                }

                $order->seller_total_shipping_fee += $this->getShippingFee($seller_id, $total_weight);
                $order->total_invoice_value += $order->seller_total_shipping_fee;

                $codCharges = Checkout::find($order->order_details_checkout_id);
                $order->total_cod_fees = round($this->percentConverter($order->invoice_value, $codCharges->cod_charge_percent), 2);
                $order->total_discount = round($this->discountConverter($order->checkout->subtotal, $order->checkout->discount, $order->invoice_value), 2);

                $discountForOrder = round(($order->total_invoice_value / $subtotal_and_shipping_cost) * $order->discount, 2);
                $order->total_invoice_value -= $discountForOrder;
                $order->final_invoice_value = ($order->invoice_value + $order->total_cod_fees + $order->seller_total_shipping_fee) - $order->total_discount;

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

                // Add the earnings of this order to the total earnings for the month
                $totalEarningsForMonth += $order->earnings;

                return [
                    'id' => $order->id,
                    'invoice_no' => $order->invoice_no,
                    'date_and_time' => $order->created_at->format('Y-m-d H:i:s'),
                    'seller_id' => $order->seller_id,
                    'country_id' => $order->country_id,
                    'customer_id' => $order->customer_id,
                    'currency' => $order->currency,
                    'final_invoice_value' => $order->final_invoice_value,
                    'seller_payable_money' => $order->seller_payable_money,
                    'earnings' => number_format($order->earnings, 2)  // Format earnings to two decimal places
                ];
            });

            // Add total earnings for this month to the group and format it
            return [
                'orders' => $ordersInMonthWithDetails,
                'total_earnings' => number_format($totalEarningsForMonth, 2)  // Format total earnings
            ];
        });


        $allMonths = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];

        // Initialize all months with 0 earnings
        $monthlyEarnings = collect($allMonths)->mapWithKeys(function ($month) use ($ordersWithDetails) {
            return [
                $month => isset($ordersWithDetails[$month])
                    ? (float) str_replace(',', '', $ordersWithDetails[$month]['total_earnings'])
                    : 0
            ];
        });




        $all_orders = Order::latest()->get();
        $total_customers = Customer::get()->count();
        $total_seller = Seller::get()->count();
        $recent_orders = Order::with('customer')->latest()->limit(10)->get();



        $all_vendors = Seller::with('orders')->latest()->select('id', 'name', 'gender')->get();
        // return $all_vendors;
        // Get the total sales for each vendor
        $vendor_sales = $all_vendors->map(function ($vendor) {
            $total_sales = $vendor->orders->sum('invoice_value');
            return [
                'name' => $vendor->name,
                'total_sales' => $total_sales,
            ];
        });

        // Extract the vendor names and sales for the chart
        $vendor_names = $vendor_sales->pluck('name');
        $vendor_totals = $vendor_sales->pluck('total_sales');


        $adminTotalSells = Order::all()->sum('invoice_value');
        // return $adminTotalSells;

        $totalProduct = Product::all()->count();



        $adminCurrency = SiteSetting::latest()->first();
        // return $this->convertCurrency($adminCurrency->value, )


        // Pass the necessary data to the view
        return view('dashboard', compact('ordersWithDetails', 'monthlyEarnings', 'all_orders', 'total_customers', 'total_seller', 'recent_orders', 'vendor_names', 'vendor_totals', 'adminTotalSells', 'totalProduct'));


        // return view('dashboard', compact('ordersWithDetails','monthlyEarnings', 'all_orders', 'total_customers', 'total_seller', 'recent_orders', 'all_vendors'));




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
            'converted_amount' => number_format($convertedAmount, 2, '.', ',')
        ];
    }





}
