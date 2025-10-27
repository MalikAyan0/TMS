<?php

namespace Database\Seeders;

use App\Models\ContainerSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContainerSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $containerSizes = [
            ['container_size' => '20ft Standard', 'status' => 'active'],
            ['container_size' => '40ft Standard', 'status' => 'active'],
            ['container_size' => '40ft High Cube', 'status' => 'active'],
            ['container_size' => '45ft High Cube', 'status' => 'active'],
            ['container_size' => '20ft Open Top', 'status' => 'inactive'],
            ['container_size' => '40ft Open Top', 'status' => 'active'],
            ['container_size' => '20ft Flat Rack', 'status' => 'active'],
            ['container_size' => '40ft Flat Rack', 'status' => 'inactive'],
        ];

        foreach ($containerSizes as $containerSize) {
            ContainerSize::create($containerSize);
        }
    }
}
