<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Step 1: Update rows with 'In Progress' to a temporary valid value
    DB::table('job_status_logs')
      ->where('status', 'In Progress')
      ->update(['status' => 'Open']);

    // Step 2: Update the ENUM definition to include 'Vehicle Required'
    DB::statement("ALTER TABLE `job_status_logs` CHANGE `status` `status` ENUM(
      'Open',
      'Vehicle Required',
      'On Route',
      'Stuck On Port',
      'Vehicle Returned',
      'Empty Return',
      'Completed',
      'Cancelled'
    ) NOT NULL");

    // Step 3: Update rows with the temporary value to 'Vehicle Required'
    DB::table('job_status_logs')
      ->where('status', 'Open')
      ->update(['status' => 'Vehicle Required']);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Step 1: Update rows with 'Vehicle Required' to a temporary valid value
    DB::table('job_status_logs')
      ->where('status', 'Vehicle Required')
      ->update(['status' => 'Open']);

    // Step 2: Revert the ENUM definition to exclude 'Vehicle Required' and include 'In Progress'
    DB::statement("ALTER TABLE `job_status_logs` CHANGE `status` `status` ENUM(
      'Open',
      'In Progress',
      'On Route',
      'Stuck On Port',
      'Vehicle Returned',
      'Empty Return',
      'Completed',
      'Cancelled'
    ) NOT NULL");

    // Step 3: Update rows with the temporary value back to 'In Progress'
    DB::table('job_status_logs')
      ->where('status', 'Open')
      ->update(['status' => 'In Progress']);
  }
};
