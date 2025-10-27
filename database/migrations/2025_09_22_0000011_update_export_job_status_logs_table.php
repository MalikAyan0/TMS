<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Enums\ExportJobStatus;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Step 1: Ensure all rows have valid status values
    $validStatuses = array_map(fn($case) => $case->value, ExportJobStatus::cases());
    DB::table('export_job_status_logs')
      ->whereNotIn('status', $validStatuses)
      ->update(['status' => ExportJobStatus::Open->value]);

    // Step 2: Update the ENUM definition for the status column
    $enumValues = implode("','", $validStatuses);
    DB::statement("ALTER TABLE `export_job_status_logs` CHANGE `status` `status` ENUM(
      '$enumValues'
    ) NOT NULL DEFAULT '" . ExportJobStatus::Open->value . "'");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Step 1: Revert rows with updated statuses to a default valid status
    DB::table('export_job_status_logs')
      ->whereNotIn('status', [
        'Open',
        'In Progress',
        'On Route',
        'Stuck On Port',
        'Vehicle Returned',
        'Empty Return',
        'Completed',
        'Cancelled'
      ])
      ->update(['status' => 'Open']);

    // Step 2: Revert the ENUM definition for the status column
    DB::statement("ALTER TABLE `export_job_status_logs` CHANGE `status` `status` ENUM(
      'Open',
      'In Progress',
      'On Route',
      'Stuck On Port',
      'Vehicle Returned',
      'Empty Return',
      'Completed',
      'Cancelled'
    ) NOT NULL DEFAULT 'Open'");
  }
};
