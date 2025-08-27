<?php

namespace Database\Seeders;

use App\Models\LoyaltyRule;
use Illuminate\Database\Seeder;

class LoyaltyRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default loyalty rule: 1 point per $10 spent, 100 points = $5 discount
        LoyaltyRule::create([
            'name' => 'Default Loyalty Program',
            'spend_amount' => 10.00,
            'earn_points' => 1,
            'redeem_points' => 100,
            'redeem_value' => 5.00,
            'is_active' => true,
        ]);

        // Create alternative rule for premium customers
        LoyaltyRule::create([
            'name' => 'Premium Loyalty Program',
            'spend_amount' => 5.00,
            'earn_points' => 1,
            'redeem_points' => 50,
            'redeem_value' => 3.00,
            'is_active' => false,
        ]);
    }
}
