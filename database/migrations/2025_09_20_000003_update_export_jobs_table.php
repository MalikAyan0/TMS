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
    DB::table('export_jobs')
      ->whereNotIn('status', $validStatuses)
      ->update(['status' => ExportJobStatus::Open->value]);

    // Step 2: Update rows with 'In Progress' to a temporary valid value
    DB::table('export_jobs')
      ->where('status', 'In Progress')
      ->update(['status' => ExportJobStatus::Open->value]);

    // Step 3: Update rows with 'Vehicle Returned' to a temporary valid value
    DB::table('export_jobs')
      ->where('status', 'Vehicle Returned')
      ->update(['status' => ExportJobStatus::Open->value]);

    // Step 4: Update the ENUM definition to use the ExportJobStatus ENUM values
    $enumValues = implode("','", $validStatuses);
    DB::statement("ALTER TABLE `export_jobs` CHANGE `status` `status` ENUM(
      '$enumValues'
    ) NOT NULL DEFAULT '" . ExportJobStatus::Open->value . "'");

    // Step 5: Update rows with the temporary value to 'Vehicle Required'
    DB::table('export_jobs')
      ->where('status', ExportJobStatus::Open->value)
      ->update(['status' => ExportJobStatus::VehicleRequired->value]);

    // Step 6: Update rows with the temporary value to 'Empty Pickuped'
    DB::table('export_jobs')
      ->where('status', ExportJobStatus::Open->value)
      ->update(['status' => ExportJobStatus::EmptyPick->value]);

    Schema::table('export_jobs', function (Blueprint $table) {
      // Add job_type column
      $table->enum('job_type', ['Empty', 'Loaded'])->default('Empty')
        ->comment('Defines if the job starts as Empty or Loaded');

      // Add status_updated_at and status_updated_by columns
      $table->timestamp('status_updated_at')->nullable();
      $table->unsignedBigInteger('status_updated_by')->nullable();

      // Add foreign key for status_updated_by
      $table->foreign('status_updated_by')->references('id')->on('users')->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('export_jobs', function (Blueprint $table) {
      // Drop job_type column
      $table->dropColumn('job_type');

      // Revert status ENUM definition
      DB::statement("ALTER TABLE `export_jobs` CHANGE `status` `status` ENUM(
        'Open',
        'In Progress',
        'On Route',
        'Stuck On Port',
        'Vehicle Returned',
        'Empty Return',
        'Completed',
        'Cancelled'
      ) NOT NULL DEFAULT 'Open'");

      // Drop status_updated_at and status_updated_by columns
      $table->dropColumn('status_updated_at');
      $table->dropForeign(['status_updated_by']);
      $table->dropColumn('status_updated_by');
    });
  }
};
