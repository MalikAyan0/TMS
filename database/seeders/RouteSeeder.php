<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RouteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get admin user ID for created_by field
    $adminUser = User::where('email', 'admin@example.com')->first()
      ?? User::orderBy('id')->first();

    if (!$adminUser) {
      throw new \Exception('No user found to set as creator for routes');
    }

    $routes = [
      [
        'route_name' => 'KICT to SKY (LOADED)',
        'origin' => 'KICT',
        'destination' => 'SKY',
        'load' => 'LOAD',
        'fuel' => '17'
      ],
      [
        'route_name' => 'KGTL to SKY (LOADED)',
        'origin' => 'KGTL',
        'destination' => 'SKY',
        'load' => 'LOAD',
        'fuel' => '18'
      ],
      [
        'route_name' => 'SAPT to SKY (LOADED)',
        'origin' => 'SAPT',
        'destination' => 'SKY',
        'load' => 'LOAD',
        'fuel' => '20'
      ],
      [
        'route_name' => 'SKY to KICT (EMPTY)',
        'origin' => 'SKY',
        'destination' => 'KICT',
        'load' => 'EMPTY',
        'fuel' => '12'
      ],
      [
        'route_name' => 'SKY to KGTL (EMPTY)',
        'origin' => 'SKY',
        'destination' => 'KGTL',
        'load' => 'EMPTY',
        'fuel' => '16'
      ],
      [
        'route_name' => 'SKY to SAPT (EMPTY)',
        'origin' => 'SKY',
        'destination' => 'SAPT',
        'load' => 'EMPTY',
        'fuel' => '14'
      ],
      [
        'route_name' => 'SKY LOAD to SKY BILAL (EMPTY)',
        'origin' => 'SKY LOAD',
        'destination' => 'SKY BILAL',
        'load' => 'EMPTY',
        'fuel' => '04'
      ],
      [
        'route_name' => 'BWT to SKY YARD (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'SKY YARD',
        'load' => 'EMPTY',
        'fuel' => '12'
      ],
      [
        'route_name' => 'BILAL FISHERY to SKY YARD (EMPTY)',
        'origin' => 'BILAL FISHERY',
        'destination' => 'SKY YARD',
        'load' => 'EMPTY',
        'fuel' => '12'
      ],
      [
        'route_name' => 'BWT to KICT (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'KICT',
        'load' => 'EMPTY',
        'fuel' => '10'
      ],
      [
        'route_name' => 'BWT to KICT (LOADED)',
        'origin' => 'BWT',
        'destination' => 'KICT',
        'load' => 'LOAD',
        'fuel' => '05'
      ],
      [
        'route_name' => 'BWT to KGTL (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'KGTL',
        'load' => 'EMPTY',
        'fuel' => '07'
      ],
      [
        'route_name' => 'BWT to KGTL (LOADED)',
        'origin' => 'BWT',
        'destination' => 'KGTL',
        'load' => 'LOAD',
        'fuel' => '08'
      ],
      [
        'route_name' => 'BWT to SAPT (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'SAPT',
        'load' => 'EMPTY',
        'fuel' => '12'
      ],
      [
        'route_name' => 'BWT to SAPT (LOADED)',
        'origin' => 'BWT',
        'destination' => 'SAPT',
        'load' => 'LOAD',
        'fuel' => '14'
      ],
      [
        'route_name' => 'BWT to TPX (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'TPX',
        'load' => 'EMPTY',
        'fuel' => '06'
      ],
      [
        'route_name' => 'BWT to UMA (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'UMA',
        'load' => 'EMPTY',
        'fuel' => '10'
      ],
      [
        'route_name' => 'BWT to ALL MARIPURE (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'ALL MARIPURE',
        'load' => 'EMPTY',
        'fuel' => '12'
      ],
      [
        'route_name' => 'BWT to BOLAN YARD (EMPTY)',
        'origin' => 'BWT',
        'destination' => 'BOLAN YARD',
        'load' => 'EMPTY',
        'fuel' => '16'
      ],
      [
        'route_name' => 'KICT to GTPL (LOADED)',
        'origin' => 'KICT',
        'destination' => 'GTPL',
        'load' => 'LOAD',
        'fuel' => '30'
      ],
      [
        'route_name' => 'KGTL to GTPL (LOADED)',
        'origin' => 'KGTL',
        'destination' => 'GTPL',
        'load' => 'LOAD',
        'fuel' => '30'
      ],
      [
        'route_name' => 'SAPT to GTPL (LOADED)',
        'origin' => 'SAPT',
        'destination' => 'GTPL',
        'load' => 'LOAD',
        'fuel' => '30'
      ],
      [
        'route_name' => 'GTPL to ALL YARD (EMPTY)',
        'origin' => 'GTPL',
        'destination' => 'ALL YARD',
        'load' => 'EMPTY',
        'fuel' => '20'
      ],
    ];

    $count = 1;
    foreach ($routes as $routeData) {
      // Create a route code with the format "RT-001", "RT-002", etc.
      $routeCode = 'RT-' . str_pad($count, 3, '0', STR_PAD_LEFT);

      // Use the provided route_name or generate one if not provided
      $routeName = $routeData['route_name'] ?? "{$routeData['origin']} to {$routeData['destination']} ({$routeData['load']})";

      // Extract the numeric value from the fuel string and convert to float
      $fuelValue = (float) $routeData['fuel'];

      // Check if this route already exists to avoid duplicates
      $exists = Route::where('route_code', $routeCode)->exists();

      if (!$exists) {
        Route::create([
          'route_name' => $routeName,
          'route_code' => $routeCode,
          'origin' => $routeData['origin'],
          'destination' => $routeData['destination'],
          'load' => $routeData['load'],  // Add this line to include the load field
          'expected_fuel' => $fuelValue,
          'status' => 'active',
          'created_by' => $adminUser->id,
        ]);
      }

      $count++;
    }

    $this->command->info('Added ' . count($routes) . ' predefined routes.');
  }
}
