<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('job_status_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('jobs_queue_id')->constrained('jobs_queue')->onDelete('cascade');
      $table->enum('status', [
        'Open',
        'In Progress',
        'On Route',
        'Stuck On Port',
        'Vehicle Returned',
        'Empty Return',
        'Completed',
        'Cancelled'
      ]);
      $table->text('remarks')->nullable();
      $table->unsignedBigInteger('changed_by'); // user who changed status
      $table->timestamps();

      // Foreign Keys
      $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('job_status_logs');
  }
};
