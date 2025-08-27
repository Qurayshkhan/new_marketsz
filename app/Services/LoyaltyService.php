<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoyaltyTransaction;
use App\Models\LoyaltyRule;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class LoyaltyService
{
    /**
     * Earn loyalty points for a purchase
     */
    public function earnPoints(User $user, Transaction $transaction, float $amount): bool
    {
        try {
            $rule = LoyaltyRule::getDefaultRule();

            if (!$rule) {
                return false;
            }

            $points = $rule->calculateEarnPoints($amount);

            if ($points <= 0) {
                return false;
            }

            DB::transaction(function () use ($user, $transaction, $points, $amount) {
                // Add points to user
                $user->addLoyaltyPoints($points);

                // Record transaction
                LoyaltyTransaction::create([
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'type' => 'earn',
                    'points' => $points,
                    'amount' => $amount,
                    'description' => "Earned {$points} points for purchase of $" . number_format($amount, 2)
                ]);
            });

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Calculate loyalty discount for given points
     */
    public function calculateLoyaltyDiscount(int $points, float $orderAmount): array
    {
        $rule = LoyaltyRule::getDefaultRule();

        if (!$rule) {
            return [
                'success' => false,
                'message' => 'Loyalty program is not configured.',
                'discount' => 0
            ];
        }

        if ($points <= 0) {
            return [
                'success' => false,
                'message' => 'Please enter a valid number of points.',
                'discount' => 0
            ];
        }

        // Calculate discount value
        $discountValue = $rule->calculateRedeemValue($points);

        // Don't discount more than order amount
        $discountValue = min($discountValue, $orderAmount);

        if ($discountValue <= 0) {
            return [
                'success' => false,
                'message' => 'Insufficient points for discount.',
                'discount' => 0
            ];
        }

        return [
            'success' => true,
            'message' => 'Loyalty discount calculated successfully!',
            'discount' => $discountValue,
            'points_required' => $points
        ];
    }

    /**
     * Redeem loyalty points
     */
    public function redeemPoints(User $user, Transaction $transaction, int $points, float $discountAmount): bool
    {
        try {
            // Check if user has enough points
            if (!$user->hasEnoughLoyaltyPoints($points)) {
                return false;
            }

            DB::transaction(function () use ($user, $transaction, $points, $discountAmount) {
                // Deduct points from user
                $user->deductLoyaltyPoints($points);

                // Record transaction
                LoyaltyTransaction::create([
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'type' => 'redeem',
                    'points' => $points,
                    'amount' => $discountAmount,
                    'description' => "Redeemed {$points} points for $" . number_format($discountAmount, 2) . " discount"
                ]);
            });

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get user loyalty summary
     */
    public function getUserLoyaltySummary(User $user): array
    {
        $totalEarned = LoyaltyTransaction::where('user_id', $user->id)
            ->where('type', 'earn')
            ->sum('points');

        $totalRedeemed = LoyaltyTransaction::where('user_id', $user->id)
            ->where('type', 'redeem')
            ->sum('points');

        $totalDiscountEarned = LoyaltyTransaction::where('user_id', $user->id)
            ->where('type', 'redeem')
            ->sum('amount');

        $loyaltyRule = LoyaltyRule::getDefaultRule();

        return [
            'current_points' => $user->loyalty_points,
            'total_earned' => $totalEarned,
            'total_redeemed' => $totalRedeemed,
            'total_discount_earned' => $totalDiscountEarned,
            'net_points' => $totalEarned - $totalRedeemed,
            'loyalty_rule' => $loyaltyRule ? [
                'spend_amount' => $loyaltyRule->spend_amount,
                'earn_points' => $loyaltyRule->earn_points,
                'redeem_points' => $loyaltyRule->redeem_points,
                'redeem_value' => $loyaltyRule->redeem_value
            ] : null
        ];
    }

    /**
     * Get user loyalty transactions
     */
    public function getUserLoyaltyTransactions(User $user, int $limit = 10): array
    {
        return LoyaltyTransaction::where('user_id', $user->id)
            ->with('transaction')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get loyalty program statistics
     */
    public function getLoyaltyStats(): array
    {
        $totalUsers = User::where('loyalty_points', '>', 0)->count();
        $totalPointsIssued = LoyaltyTransaction::where('type', 'earn')->sum('points');
        $totalPointsRedeemed = LoyaltyTransaction::where('type', 'redeem')->sum('points');
        $totalDiscountGiven = LoyaltyTransaction::where('type', 'redeem')->sum('amount');

        return [
            'total_users_with_points' => $totalUsers,
            'total_points_issued' => $totalPointsIssued,
            'total_points_redeemed' => $totalPointsRedeemed,
            'total_discount_given' => $totalDiscountGiven,
            'active_points' => $totalPointsIssued - $totalPointsRedeemed
        ];
    }

    /**
     * Get maximum redeemable points for user and order amount
     */
    public function getMaxRedeemablePoints(User $user, float $orderAmount): int
    {
        $rule = LoyaltyRule::getDefaultRule();

        if (!$rule) {
            return 0;
        }

        $maxRedeemable = $rule->getMaxRedeemablePoints($orderAmount);
        $userPoints = $user->loyalty_points;

        return min($maxRedeemable, $userPoints);
    }
}
