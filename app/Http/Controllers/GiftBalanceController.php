<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\GiftBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiftBalanceController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        $giftBalances = GiftBalance::with(['customer', 'currency'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gift_balance', compact('currencies', 'giftBalances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_email' => 'required|array',
            'customer_email.*' => 'required|exists:customers,id',
            'description' => 'required|array',
            'description.*' => 'required|string',
            'currency_id' => 'required|array',
            'currency_id.*' => 'required|exists:currencies,id',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0.01',
            'status' => 'required|array',
            'status.*' => 'required|in:gift_voucher,bonus,refund'
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->customer_email as $key => $customerId) {
                GiftBalance::create([
                    'customer_id' => $customerId,
                    'description' => $request->description[$key],
                    'currency_id' => $request->currency_id[$key],
                    'in' => $request->amount[$key],
                    'out' => 0,
                    'status' => $request->status[$key]
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Gift balances added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to add gift balances. Please try again.');
        }
    }

    public function edit($id)
    {
        $giftBalance = GiftBalance::with(['customer', 'currency'])->findOrFail($id);
        $currencies = Currency::all();

        return view('gift_balance_edit', compact('giftBalance', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'description' => 'required|string',
            'currency_id' => 'required|exists:currencies,id',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:gift_voucher,bonus,refund'
        ]);

        $giftBalance = GiftBalance::findOrFail($id);

        try {
            $giftBalance->update([
                'customer_id' => $request->customer_id,
                'description' => $request->description,
                'currency_id' => $request->currency_id,
                'in' => $request->amount,
                'status' => $request->status
            ]);

            return redirect()->route('gift-balance.index')->with('success', 'Gift balance updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update gift balance. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $giftBalance = GiftBalance::findOrFail($id);
            $giftBalance->delete();

            return redirect()->route('gift-balance.index')->with('success', 'Gift balance deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete gift balance. Please try again.');
        }
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->get('q');

        $customers = Customer::where(function($query) use ($search) {
            $query->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
        })
        ->limit(10)
        ->get();

        $formattedCustomers = $customers->map(function($customer) {
            return [
                'id' => $customer->id,
                'text' => "{$customer->name} ({$customer->email}) - {$customer->phone}"
            ];
        });

        return response()->json([
            'items' => $formattedCustomers,
            'total_count' => $customers->count()
        ]);
    }
}
