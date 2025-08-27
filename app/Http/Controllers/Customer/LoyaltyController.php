<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class LoyaltyController extends Controller
{
    public function __construct(
        private LoyaltyService $loyaltyService
    ) {
    }

    /**
     * Display customer loyalty dashboard
     */
    public function dashboard(): Response
    {
        $user = auth()->user();
        $summary = $this->loyaltyService->getUserLoyaltySummary($user);
        $transactions = $this->loyaltyService->getUserLoyaltyTransactions($user, 10);

        return Inertia::render('Customer/Loyalty/Dashboard', [
            'summary' => $summary,
            'transactions' => $transactions
        ]);
    }

    /**
     * Calculate loyalty discount
     */
    public function calculateDiscount(Request $request): JsonResponse
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'order_amount' => 'required|numeric|min:0'
        ]);

        $points = (int) $request->points;
        $orderAmount = (float) $request->order_amount;

        $result = $this->loyaltyService->calculateLoyaltyDiscount($points, $orderAmount);

        return response()->json($result);
    }

    /**
     * Get user's loyalty summary
     */
    public function summary(): JsonResponse
    {
        $user = auth()->user();
        $summary = $this->loyaltyService->getUserLoyaltySummary($user);

        return response()->json([
            'success' => true,
            'summary' => $summary
        ]);
    }

    /**
     * Get user's loyalty transactions
     */
    public function transactions(Request $request): JsonResponse
    {
        $user = auth()->user();
        $limit = $request->get('limit', 10);
        $transactions = $this->loyaltyService->getUserLoyaltyTransactions($user, $limit);

        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    /**
     * Get maximum redeemable points for order amount
     */
    public function maxRedeemable(Request $request): JsonResponse
    {
        $request->validate([
            'order_amount' => 'required|numeric|min:0'
        ]);

        $user = auth()->user();
        $orderAmount = (float) $request->order_amount;

        $maxPoints = $this->loyaltyService->getMaxRedeemablePoints($user, $orderAmount);

        return response()->json([
            'success' => true,
            'max_points' => $maxPoints
        ]);
    }
}
