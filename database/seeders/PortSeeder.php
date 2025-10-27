<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
  public function run()
  {
    $ports = [
      ['name' => 'Karachi Port', 'description' => 'Karachi Port Description', 'status' => 'active'],
      ['name' => 'Port Qasim', 'description' => 'Port Qasim Description', 'status' => 'active'],
      ['name' => 'Gwadar Port', 'description' => 'Gwadar Port Description', 'status' => 'active'],
      ['name' => 'Bin Qasim Terminal', 'description' => 'Bin Qasim Terminal Description', 'status' => 'active'],
    ];

    foreach ($ports as $port) {
      Port::create($port);
    }
  }
}
