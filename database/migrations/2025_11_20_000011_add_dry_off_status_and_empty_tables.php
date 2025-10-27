<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Enums\ExportJobStatus;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Step 1: Empty the export_job_status_logs table first (child table)
    DB::table('export_job_status_logs')->truncate();

    // Step 2: Empty the export_jobs table (parent table)
    DB::table('export_jobs')->delete();

    // Step 3: Add Dry Off to ExportJobStatus enum if using PHP 8.1+ Enums
    // Note: You need to manually update the ExportJobStatus enum class

    // Step 4: Add the new status to the export_jobs table
    DB::statement("ALTER TABLE export_jobs MODIFY COLUMN status ENUM(
            'Open',
            'Vehicle Required',
            'Ready To Move',
            'On Route',
            'Empty Pick',
            'Container Returned',
            'Dry Off',
            'Completed',
            'Cancelled'
        ) DEFAULT 'Open'");

    // Step 5: Add the new status to the export_job_status_logs table
    DB::statement("ALTER TABLE export_job_status_logs MODIFY COLUMN status ENUM(
            'Open',
            'Vehicle Required',
            'Ready To Move',
            'On Route',
            'Empty Pick',
            'Container Returned',
            'Dry Off',
            'Completed',
            'Cancelled'
        )");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Remove Dry Off from the export_jobs table
    DB::statement("ALTER TABLE export_jobs MODIFY COLUMN status ENUM(
            'Open',
            'Vehicle Required',
            'Ready To Move',
            'On Route',
            'Empty Pick',
            'Container Returned',
            'Completed',
            'Cancelled'
        ) DEFAULT 'Open'");

    // Remove Dry Off from the export_job_status_logs table
    DB::statement("ALTER TABLE export_job_status_logs MODIFY COLUMN status ENUM(
            'Open',
            'Vehicle Required',
            'Ready To Move',
            'On Route',
            'Empty Pick',
            'Container Returned',
            'Completed',
            'Cancelled'
        )");
  }
};
