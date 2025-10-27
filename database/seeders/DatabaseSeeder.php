<?php

namespace Database\Seeders;

use App\Models\Route;

use App\Models\Company;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Seed user statuses first
    $this->call([
      UserStatusSeeder::class,
      AdminUserSeeder::class,
      RolesAndPermissionsSeeder::class,
      FuelTypeSeeder::class,
      LocationSeeder::class,
      NatureOfAccountSeeder::class,
      OilTypeSeeder::class,
      OperationSeeder::class,
      PartySeeder::class,
      ProjectSeeder::class,
      SalesTaxTerritorySeeder::class,
      VoucherTypeSeeder::class,

      RouteSeeder::class,

      CompanySeeder::class,
      PortSeeder::class,
      LineSeeder::class,
      ManufacturerSeeder::class,  // Add manufacturer seeder first
      FleetTypeSeeder::class,     // Then fleet type seeder
      FleetSeeder::class,         // Finally fleet seeder
      BailNumberSeeder::class,    // Add bail number seeder
    ]);

    User::factory(10)->create();

    User::factory()->create([
      'name' => 'Test User',
      'email' => 'test@example.com',
    ]);
  }
}
