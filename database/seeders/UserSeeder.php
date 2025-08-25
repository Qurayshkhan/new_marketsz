<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'user',
            'email' => 'admin@marketz.com',
            'password' => Hash::make('password'),
            'type' => User::USER_TYPE_ADMIN,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        User::create([
            'first_name' => 'warehouse',
            'last_name' => 'user',
            'email' => 'warehouse@marketz.com',
            'password' => Hash::make('password'),
            'type' => User::USER_TYPE_WAREHOUSE,
            'is_active' => User::STATUS_ACTIVE,
        ]);
        User::create([
            'first_name' => 'Customer',
            'last_name' => 'user',
            'email' => 'customer@marketz.com',
            'password' => Hash::make('password'),
            'type' => User::USER_TYPE_CUSTOMER,
            'is_active' => User::STATUS_ACTIVE,
            'suite' => 'XC34311',
        ]);
    }
}
