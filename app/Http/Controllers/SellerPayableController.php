<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerPayable;

class SellerPayableController extends Controller
{
    public function updateData(Request $request)
    {
        // Define the parameters and their corresponding request keys
        $parameters = [
            'tax' => ['added' => 'tax_added', 'subtracted' => 'tax_subtracted'],
            'shipping_fee' => ['added' => 'shipping_fee_added', 'subtracted' => 'shipping_fee_subtracted'],
            'cod_charge' => ['added' => 'cod_charge_added', 'subtracted' => 'cod_charge_subtracted'],
            'coupon_discount' => ['added' => 'coupon_discount_added', 'subtracted' => 'coupon_discount_subtracted'],
            'commision' => ['added' => 'commission_added', 'subtracted' => 'commission_subtracted'],
            'promoter_fee' => ['added' => 'promoter_fee_added', 'subtracted' => 'promoter_fee_subtracted'],
            'vat_on_fee' => ['added' => 'vat_on_fee_added', 'subtracted' => 'vat_on_fee_subtracted'],
        ];

        $data = [];
        foreach ($parameters as $column => $keys) {
            if ($request->has($keys['added'])) {
                $data[$column] = 'added';
            } elseif ($request->has($keys['subtracted'])) {
                $data[$column] = 'subtracted';
            } else {
                $data[$column] = 'not_selected';
            }
        }

        SellerPayable::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Seller payable data updated successfully.');
    }
}
