<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OilType;

class OilTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oilTypes = [
            ['label' => 'Engine Oil', 'status' => 'active'],
            ['label' => 'Transmission Oil', 'status' => 'active'],
            ['label' => 'Brake Oil', 'status' => 'active'],
            ['label' => 'Hydraulic Oil', 'status' => 'active'],
            ['label' => 'Gear Oil', 'status' => 'active'],
            ['label' => 'Coolant', 'status' => 'active'],
            ['label' => 'Power Steering Oil', 'status' => 'inactive'],
            ['label' => 'Differential Oil', 'status' => 'inactive'],
        ];

        foreach ($oilTypes as $oilType) {
            OilType::create($oilType);
        }
    }
}
