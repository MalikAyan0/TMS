<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesSeeder extends Seeder
{
  public function run(): void
  {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $adminRole = Role::firstOrCreate(['name' => 'Admin']);
    $adminRole->givePermissionTo(Permission::all());

    $user = User::find(1);
    if ($user) {
      $user->assignRole($adminRole);
      $this->command->info(" Assigned 'Admin' role to user ID 1 ({$user->name}).");
    } else {
      $this->command->warn("⚠ User with ID 1 not found. Skipped role assignment.");
    }

    $this->command->info(' Roles seeded successfully.');
  }
}
