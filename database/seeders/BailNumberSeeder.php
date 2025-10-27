<?php

namespace Database\Seeders;

use App\Models\BailNumber;
use Illuminate\Database\Seeder;

class BailNumberSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $bailNumbers = [
      ['bail_number' => 'BN-001', 'description' => 'First bail number', 'status' => 'active'],
      ['bail_number' => 'BN-002', 'description' => 'Second bail number', 'status' => 'active'],
      ['bail_number' => 'BN-003', 'description' => 'Third bail number', 'status' => 'active'],
      ['bail_number' => 'BN-004', 'description' => 'Fourth bail number', 'status' => 'active'],
      ['bail_number' => 'BN-005', 'description' => 'Fifth bail number', 'status' => 'active'],
    ];

    foreach ($bailNumbers as $bailData) {
      BailNumber::create($bailData);
    }

    $this->command->info('Added ' . count($bailNumbers) . ' bail numbers.');
  }
}
