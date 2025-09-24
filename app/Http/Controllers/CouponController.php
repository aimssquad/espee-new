<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is not valid or has expired.'
            ]);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon does not apply to this order amount.'
            ]);
        }

        return response()->json([
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount
            ]
        ]);
    }
}
