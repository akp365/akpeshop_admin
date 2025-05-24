<?php

namespace App\Http\Controllers;

use App\Models\GiftBalance;
use App\Models\Currency;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GiftBalanceController extends Controller
{
    public function index()
    {
        $currencies = Currency::where('status', 'active')
                            ->orderBy('title')
                            ->get();
        $giftBalances = GiftBalance::with(['customer', 'currency'])->latest()->get();

        // Debug information
        Log::info('Currencies count: ' . $currencies->count());
        Log::info('Gift Balances count: ' . $giftBalances->count());

        return view('gift_balance', compact('currencies', 'giftBalances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_email' => 'required|array',
            'customer_email.*' => 'required|email|exists:customers,email',
            'description' => 'required|array',
            'description.*' => 'required|string',
            'currency_id' => 'required|array',
            'currency_id.*' => 'required|exists:currencies,id',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'status' => 'required|array',
            'status.*' => 'required|in:gift_voucher,bonus,refund'
        ]);

        foreach ($request->customer_email as $index => $email) {
            $customer = Customer::where('email', $email)->first();

            if ($customer) {
                GiftBalance::create([
                    'user_id' => $customer->id,
                    'description' => $request->description[$index],
                    'currency_id' => $request->currency_id[$index],
                    'in' => $request->amount[$index],
                    'status' => $request->status[$index],
                    'added_cost_by' => Auth::id()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Gift balances added successfully');
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->get('q');
        $page = $request->get('page', 1);
        $perPage = 30;

        $customers = Customer::where(function($query) use ($search) {
            $query->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
        })
        ->select('id', 'email', 'name', 'phone')
        ->orderBy('email')
        ->paginate($perPage);

        // Debug information
        Log::info('Customer search query: ' . $search);
        Log::info('Customers found: ' . $customers->count());

        return response()->json([
            'items' => $customers->map(function($customer) {
                return [
                    'id' => $customer->email,
                    'text' => $customer->email . ' - ' . $customer->name . ' (' . $customer->phone . ')'
                ];
            }),
            'total_count' => $customers->total()
        ]);
    }

    // Helper method to get initial customers for preloading
    public function getInitialCustomers()
    {
        $customers = Customer::select('id', 'email', 'name', 'phone')
            ->orderBy('email')
            ->limit(100)
            ->get();

        return response()->json([
            'items' => $customers->map(function($customer) {
                return [
                    'id' => $customer->email,
                    'text' => $customer->email . ' - ' . $customer->name . ' (' . $customer->phone . ')'
                ];
            })
        ]);
    }
}
