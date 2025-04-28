<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        return view('discount');
    }

    public function calculate(Request $request)
    {
        $mode = $request->mode ?? 'single';

        $rules = [
            'price' => 'required|numeric|min:1',
            'discount' => 'required|numeric|min:0|max:100',
        ];

        if ($mode === 'double') {
            $rules['discount_b'] = 'required|numeric|min:0|max:100';
        }

        $request->validate($rules);

        $price = $request->price;
        $discountPercent = $request->discount;

        if ($mode === 'single') {
            // Diskon tunggal
            $discountValue = $price * ($discountPercent / 100);
            $totalPrice = $price - $discountValue;

            return view('discount', [
                'mode' => 'single',
                'price' => $price,
                'discountPercent' => $discountPercent,
                'discountValue' => $discountValue,
                'totalPrice' => $totalPrice
            ]);
        } else {
            // Diskon A% + B%
            $discountBPercent = $request->discount_b;

            // Hitung diskon A
            $discountValue = $price * ($discountPercent / 100);
            $priceAfterA = $price - $discountValue;

            // Hitung diskon B berdasarkan harga setelah diskon A
            $discountBValue = $priceAfterA * ($discountBPercent / 100);
            $totalPrice = $priceAfterA - $discountBValue;

            return view('discount', [
                'mode' => 'double',
                'price' => $price,
                'discountPercent' => $discountPercent,
                'discountValue' => $discountValue,
                'discountBPercent' => $discountBPercent,
                'discountBValue' => $discountBValue,
                'totalPrice' => $totalPrice
            ]);
        }
    }
}