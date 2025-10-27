<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserStatus;
use Carbon\Carbon;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'label' => 'Active',
                'color' => 'success',
                'description' => 'User is currently active and can access the system',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Inactive',
                'color' => 'secondary',
                'description' => 'User is temporarily inactive',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Pending',
                'color' => 'warning',
                'description' => 'User registration pending approval',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Suspended',
                'color' => 'danger',
                'description' => 'User account suspended due to policy violation',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Verified',
                'color' => 'primary',
                'description' => 'User account verified and authenticated',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Blocked',
                'color' => 'danger',
                'description' => 'User account permanently blocked',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Trial',
                'color' => 'info',
                'description' => 'User is on trial period',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'label' => 'Expired',
                'color' => 'warning',
                'description' => 'User account subscription expired',
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($statuses as $status) {
            UserStatus::updateOrCreate(
                ['label' => $status['label']],
                $status
            );
        }

        $this->command->info('User statuses seeded successfully!');
    }
}
