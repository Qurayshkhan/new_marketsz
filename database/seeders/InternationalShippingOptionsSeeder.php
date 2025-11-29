<?php

namespace Database\Seeders;

use App\Models\InternationalShippingOptions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternationalShippingOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // InternationalShippingOptions::truncate();
        $option = [
            [
                'title' => 'DHL Express',
                'description' => '2 to 4 business days',
            ],
            [
                'title' => 'FedEx Economy',
                'description' => '3 to 5 business days',
            ],
            [
                'title' => 'Sea-freight',
                'description' => '7 to 9 business days',
            ],
            [
                'title' => 'Air-Cargo',
                'description' => '4 to 6 business days',
            ],
        ];
        InternationalShippingOptions::insert($option);
    }
}
