<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'usage_limit',
        'used_count',
        'expiry_date',
        'is_active',
        'description'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['is_valid', 'is_expired'];

    /**
     * Get coupon usages
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if coupon is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if coupon is valid (not expired, active, and within usage limit)
     */
    public function getIsValidAttribute(): bool
    {
        return $this->is_active &&
            !$this->is_expired &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    /**
     * Check if coupon can be used for a given order amount
     */
    public function canBeUsedForAmount(float $amount): bool
    {
        return $this->is_valid && $amount >= $this->minimum_order_amount;
    }

    /**
     * Calculate discount amount for a given order amount
     */
    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->canBeUsedForAmount($orderAmount)) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            return min($discount, $orderAmount); // Don't discount more than order amount
        }

        return min($this->discount_value, $orderAmount); // Fixed amount, but don't exceed order amount
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Scope for active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid coupons (not expired)
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
                ->orWhere('expiry_date', '>', Carbon::now());
        });
    }

    /**
     * Scope for available coupons (within usage limit)
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('usage_limit')
                ->orWhereRaw('used_count < usage_limit');
        });
    }
}
