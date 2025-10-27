<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Cross-Country Delivery System',
                'short_name' => 'CCDS',
                'status' => 'active',
            ],
            [
                'title' => 'Urban Transport Network',
                'short_name' => 'UTN',
                'status' => 'active',
            ],
            [
                'title' => 'Freight Management Portal',
                'short_name' => 'FMP',
                'status' => 'pending',
            ],
            [
                'title' => 'Fleet Optimization System',
                'short_name' => 'FOS',
                'status' => 'completed',
            ],
            [
                'title' => 'Warehouse Automation Project',
                'short_name' => 'WAP',
                'status' => 'active',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
