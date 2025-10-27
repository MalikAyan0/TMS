<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operation;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = [
            [
                'label' => 'Container Loading Operation',
                'status' => 'active',
            ],
            [
                'label' => 'Cargo Sorting Process',
                'status' => 'active',
            ],
            [
                'label' => 'Vehicle Maintenance Check',
                'status' => 'inactive',
            ],
            [
                'label' => 'Package Inspection',
                'status' => 'active',
            ],
            [
                'label' => 'Emergency Cargo Transport',
                'status' => 'active',
            ],
            [
                'label' => 'Warehouse Inventory Count',
                'status' => 'inactive',
            ],
        ];

        foreach ($operations as $operation) {
            Operation::create($operation);
        }
    }
}
