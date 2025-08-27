<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Welcome coupon - 10% off for new customers
        Coupon::create([
            'code' => 'WELCOME10',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'minimum_order_amount' => 25.00,
            'usage_limit' => 1000,
            'expiry_date' => now()->addMonths(3),
            'is_active' => true,
            'description' => 'Welcome discount for new customers - 10% off orders over $25',
        ]);

        // Free shipping coupon
        Coupon::create([
            'code' => 'FREESHIP',
            'discount_type' => 'fixed',
            'discount_value' => 15.00,
            'minimum_order_amount' => 50.00,
            'usage_limit' => 500,
            'expiry_date' => now()->addMonths(2),
            'is_active' => true,
            'description' => 'Free shipping on orders over $50',
        ]);

        // Holiday special - 20% off
        Coupon::create([
            'code' => 'HOLIDAY20',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'minimum_order_amount' => 100.00,
            'usage_limit' => 200,
            'expiry_date' => now()->addMonths(1),
            'is_active' => true,
            'description' => 'Holiday special - 20% off orders over $100',
        ]);

        // Loyalty reward coupon - $5 off
        Coupon::create([
            'code' => 'LOYALTY5',
            'discount_type' => 'fixed',
            'discount_value' => 5.00,
            'minimum_order_amount' => 30.00,
            'usage_limit' => null, // Unlimited for loyalty members
            'expiry_date' => now()->addYear(),
            'is_active' => true,
            'description' => 'Loyalty member reward - $5 off any order over $30',
        ]);

        // Expired coupon for testing
        Coupon::create([
            'code' => 'EXPIRED',
            'discount_type' => 'percentage',
            'discount_value' => 15.00,
            'minimum_order_amount' => 0.00,
            'usage_limit' => 100,
            'expiry_date' => now()->subDays(30),
            'is_active' => false,
            'description' => 'Expired coupon for testing purposes',
        ]);
    }
}
