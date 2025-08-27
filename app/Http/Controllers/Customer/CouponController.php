<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    public function __construct(
        private CouponService $couponService
    ) {
    }

    /**
     * Validate and apply coupon
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'order_amount' => 'required|numeric|min:0'
        ]);

        $user = auth()->user();
        $code = strtoupper($request->code);
        $orderAmount = (float) $request->order_amount;

        $result = $this->couponService->validateAndApplyCoupon($code, $user, $orderAmount);

        return response()->json($result);
    }

    /**
     * Get user's coupon usage history
     */
    public function history(): JsonResponse
    {
        $user = auth()->user();
        $usage = $this->couponService->getUserCouponUsage($user);

        return response()->json([
            'success' => true,
            'usage' => $usage
        ]);
    }
}
