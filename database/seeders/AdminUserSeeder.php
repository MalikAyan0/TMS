<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First create an Active status if it doesn't exist
        $activeStatus = UserStatus::firstOrCreate([
            'label' => 'Active'
        ], [
            'color' => 'success',
            'description' => 'Active user status',
            'status' => 'active',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'status_id' => $activeStatus->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Create a test user
        User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'status_id' => $activeStatus->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
