<?php

namespace App\Repositories;

use App\Models\LoyaltyRule;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LoyaltyRepository
{
    /**
     * Get all loyalty rules
     */
    public function getAllRules(): Collection
    {
        return LoyaltyRule::orderBy('created_at', 'desc')->get();
    }

    /**
     * Get active loyalty rule
     */
    public function getActiveRule(): ?LoyaltyRule
    {
        return LoyaltyRule::active()->first();
    }

    /**
     * Create new loyalty rule
     */
    public function createRule(array $data): LoyaltyRule
    {
        return LoyaltyRule::create($data);
    }

    /**
     * Update loyalty rule
     */
    public function updateRule(LoyaltyRule $rule, array $data): bool
    {
        return $rule->update($data);
    }

    /**
     * Delete loyalty rule
     */
    public function deleteRule(LoyaltyRule $rule): bool
    {
        return $rule->delete();
    }

    /**
     * Get user loyalty transactions with pagination
     */
    public function getUserTransactions(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return LoyaltyTransaction::where('user_id', $user->id)
            ->with('transaction')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all loyalty transactions with pagination
     */
    public function getAllTransactions(int $perPage = 15): LengthAwarePaginator
    {
        return LoyaltyTransaction::with(['user', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get users with loyalty points
     */
    public function getUsersWithPoints(int $perPage = 15): LengthAwarePaginator
    {
        return User::where('loyalty_points', '>', 0)
            ->orderBy('loyalty_points', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get loyalty statistics
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
     * Get top loyalty users
     */
    public function getTopLoyaltyUsers(int $limit = 10): Collection
    {
        return User::where('loyalty_points', '>', 0)
            ->orderBy('loyalty_points', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent loyalty transactions
     */
    public function getRecentTransactions(int $limit = 10): Collection
    {
        return LoyaltyTransaction::with(['user', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search loyalty transactions
     */
    public function searchTransactions(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return LoyaltyTransaction::whereHas('user', function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        })
            ->with(['user', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
