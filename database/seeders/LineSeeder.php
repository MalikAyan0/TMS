<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Line;

class LineSeeder extends Seeder
{
  public function run()
  {
    $lines = [
      ['name' => 'Maersk Line', 'description' => 'Maersk Line Description', 'status' => 'active'],
      ['name' => 'MSC', 'description' => 'MSC Description', 'status' => 'active'],
      ['name' => 'Hapag-Lloyd', 'description' => 'Hapag-Lloyd Description', 'status' => 'active'],
      ['name' => 'COSCO', 'description' => 'COSCO Description', 'status' => 'active'],
    ];

    foreach ($lines as $line) {
      Line::create($line);
    }
  }
}
