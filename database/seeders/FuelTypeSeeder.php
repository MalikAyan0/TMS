<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FuelType;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuelTypes = [
            ['label' => 'Diesel', 'status' => 'active'],
            ['label' => 'Gasoline', 'status' => 'active'],
            ['label' => 'Electric', 'status' => 'active'],
            ['label' => 'Hybrid', 'status' => 'active'],
            ['label' => 'CNG', 'status' => 'active'],
            ['label' => 'LPG', 'status' => 'active'],
            ['label' => 'Biodiesel', 'status' => 'inactive'],
            ['label' => 'Ethanol', 'status' => 'inactive'],
        ];

        foreach ($fuelTypes as $fuelType) {
            FuelType::create($fuelType);
        }
    }
}
