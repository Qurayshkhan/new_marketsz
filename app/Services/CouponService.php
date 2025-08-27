<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class CouponService
{
    /**
     * Validate and apply a coupon
     */
    public function validateAndApplyCoupon(string $code, User $user, float $orderAmount): array
    {
        try {
            // Find the coupon
            $coupon = Coupon::where('code', $code)->first();

            if (!$coupon) {
                return [
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                    'discount' => 0
                ];
            }

            // Check if coupon is valid
            if (!$coupon->is_valid) {
                return [
                    'success' => false,
                    'message' => 'This coupon is no longer valid.',
                    'discount' => 0
                ];
            }

            // Check minimum order amount
            if ($orderAmount < $coupon->minimum_order_amount) {
                return [
                    'success' => false,
                    'message' => "Minimum order amount of $" . number_format($coupon->minimum_order_amount, 2) . " required.",
                    'discount' => 0
                ];
            }

            // Check if user has already used this coupon
            $existingUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingUsage) {
                return [
                    'success' => false,
                    'message' => 'You have already used this coupon.',
                    'discount' => 0
                ];
            }

            // Calculate discount
            $discount = $coupon->calculateDiscount($orderAmount);

            return [
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => $discount,
                'coupon' => $coupon
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred while validating the coupon.',
                'discount' => 0
            ];
        }
    }

    /**
     * Record coupon usage
     */
    public function recordCouponUsage(Coupon $coupon, User $user, Transaction $transaction, float $discountAmount, float $orderAmount): bool
    {
        try {
            DB::transaction(function () use ($coupon, $user, $transaction, $discountAmount, $orderAmount) {
                // Create usage record
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'discount_amount' => $discountAmount,
                    'order_amount' => $orderAmount
                ]);

                // Increment coupon usage count
                $coupon->incrementUsage();
            });

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get coupon usage statistics
     */
    public function getCouponUsageStats(): array
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::active()->count();
        $expiredCoupons = Coupon::where('expiry_date', '<', now())->count();
        $totalUsage = CouponUsage::count();

        return [
            'total_coupons' => $totalCoupons,
            'active_coupons' => $activeCoupons,
            'expired_coupons' => $expiredCoupons,
            'total_usage' => $totalUsage
        ];
    }

    /**
     * Get coupon usage by user
     */
    public function getUserCouponUsage(User $user): array
    {
        return CouponUsage::with('coupon')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Generate unique coupon code
     */
    public function generateUniqueCode(int $length = 8): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }
}
