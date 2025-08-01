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
            'first_name' => 'Test',
            'last_name' => 'admin',
            'email' => 'admin@marketz.com',
            'password' => Hash::make('password'),
            'type' => 1,
        ]);
    }
}
