<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CouponController extends Controller
{
    public function __construct(
        private CouponRepository $couponRepository,
        private CouponService $couponService
    ) {
    }

    /**
     * Display a listing of coupons
     */
    public function index(Request $request): Response
    {
        $query = $request->get('search');

        if ($query) {
            $coupons = $this->couponRepository->search($query);
        } else {
            $coupons = $this->couponRepository->getAllPaginated();
        }

        $stats = $this->couponRepository->getUsageStats();

        return Inertia::render('Admin/Coupons/Index', [
            'coupons' => $coupons,
            'stats' => $stats,
            'search' => $query
        ]);
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Coupons/Create');
    }

    /**
     * Store a newly created coupon
     */
    public function store(CouponRequest $request)
    {
        try {
            $data = $request->validated();

            // Generate unique code if not provided
            if (empty($data['code'])) {
                $data['code'] = $this->couponService->generateUniqueCode();
            }

            $this->couponRepository->create($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create coupon.']);
        }
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon): Response
    {
        return Inertia::render('Admin/Coupons/Edit', [
            'coupon' => $coupon
        ]);
    }

    /**
     * Update the specified coupon
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        try {
            $data = $request->validated();
            $this->couponRepository->update($coupon, $data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update coupon.']);
        }
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $this->couponRepository->delete($coupon);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete coupon.']);
        }
    }

    /**
     * Toggle coupon status
     */
    public function toggleStatus(Coupon $coupon)
    {
        try {
            $coupon->update(['is_active' => !$coupon->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Coupon status updated successfully!',
                'is_active' => $coupon->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon status.'
            ], 500);
        }
    }

    /**
     * Show coupon usage statistics
     */
    public function usageStats(): Response
    {
        $stats = $this->couponRepository->getUsageStats();
        $mostUsed = $this->couponRepository->getMostUsedCoupons();
        $expiringSoon = $this->couponRepository->getExpiringSoon();

        return Inertia::render('Admin/Coupons/Stats', [
            'stats' => $stats,
            'mostUsed' => $mostUsed,
            'expiringSoon' => $expiringSoon
        ]);
    }

    /**
     * Generate unique coupon code
     */
    public function generateCode()
    {
        $code = $this->couponService->generateUniqueCode();

        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }
}
