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
        $request->validate([
            'price' => 'required|numeric|min:1',
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        $price = $request->price;
        $discountPercent = $request->discount;
        $discountValue = $price * ($discountPercent / 100);
        $totalPrice = $price - $discountValue;

        return view('discount', [
            'price' => $price,
            'discountPercent' => $discountPercent,
            'discountValue' => $discountValue,
            'totalPrice' => $totalPrice
        ]);
    }
}