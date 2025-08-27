<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyRuleRequest;
use App\Models\LoyaltyRule;
use App\Repositories\LoyaltyRepository;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LoyaltyController extends Controller
{
    public function __construct(
        private LoyaltyRepository $loyaltyRepository,
        private LoyaltyService $loyaltyService
    ) {
    }

    /**
     * Display loyalty program dashboard
     */
    public function index(): Response
    {
        $stats = $this->loyaltyRepository->getLoyaltyStats();
        $topUsers = $this->loyaltyRepository->getTopLoyaltyUsers();
        $recentTransactions = $this->loyaltyRepository->getRecentTransactions();

        return Inertia::render('Admin/Loyalty/Index', [
            'stats' => $stats,
            'topUsers' => $topUsers,
            'recentTransactions' => $recentTransactions
        ]);
    }

    /**
     * Display loyalty rules management
     */
    public function rules(): Response
    {
        $rules = $this->loyaltyRepository->getAllRules();
        $activeRule = $this->loyaltyRepository->getActiveRule();

        return Inertia::render('Admin/Loyalty/Rules', [
            'rules' => $rules,
            'activeRule' => $activeRule
        ]);
    }

    /**
     * Store a new loyalty rule
     */
    public function storeRule(LoyaltyRuleRequest $request)
    {
        try {
            $data = $request->validated();

            // If this rule is being set as active, deactivate others
            if ($data['is_active']) {
                LoyaltyRule::where('is_active', true)->update(['is_active' => false]);
            }

            $this->loyaltyRepository->createRule($data);

            return redirect()->route('admin.loyalty.rules')
                ->with('success', 'Loyalty rule created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create loyalty rule.']);
        }
    }

    /**
     * Update loyalty rule
     */
    public function updateRule(LoyaltyRuleRequest $request, LoyaltyRule $rule)
    {
        try {
            $data = $request->validated();

            // If this rule is being set as active, deactivate others
            if ($data['is_active']) {
                LoyaltyRule::where('id', '!=', $rule->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $this->loyaltyRepository->updateRule($rule, $data);

            return redirect()->route('admin.loyalty.rules')
                ->with('success', 'Loyalty rule updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update loyalty rule.']);
        }
    }

    /**
     * Delete loyalty rule
     */
    public function destroyRule(LoyaltyRule $rule)
    {
        try {
            $this->loyaltyRepository->deleteRule($rule);

            return redirect()->route('admin.loyalty.rules')
                ->with('success', 'Loyalty rule deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete loyalty rule.']);
        }
    }

    /**
     * Display loyalty transactions
     */
    public function transactions(Request $request): Response
    {
        $query = $request->get('search');

        if ($query) {
            $transactions = $this->loyaltyRepository->searchTransactions($query);
        } else {
            $transactions = $this->loyaltyRepository->getAllTransactions();
        }

        return Inertia::render('Admin/Loyalty/Transactions', [
            'transactions' => $transactions,
            'search' => $query
        ]);
    }

    /**
     * Display users with loyalty points
     */
    public function users(): Response
    {
        $users = $this->loyaltyRepository->getUsersWithPoints();

        return Inertia::render('Admin/Loyalty/Users', [
            'users' => $users
        ]);
    }

    /**
     * Toggle loyalty rule status
     */
    public function toggleRuleStatus(LoyaltyRule $rule)
    {
        try {
            // If activating this rule, deactivate others
            if (!$rule->is_active) {
                LoyaltyRule::where('id', '!=', $rule->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $rule->update(['is_active' => !$rule->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Loyalty rule status updated successfully!',
                'is_active' => $rule->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update loyalty rule status.'
            ], 500);
        }
    }
}
