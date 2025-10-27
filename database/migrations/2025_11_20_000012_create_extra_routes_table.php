<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('extra_routes', function (Blueprint $table) {
      $table->id();

      // Reference to the related record
      $table->unsignedBigInteger('reference_id');
      $table->enum('reference_type', ['export_logistics', 'job_empty_return']);

      // Job reference - the main job this extra route is associated with
      $table->unsignedBigInteger('job_id');

      // Route information
      $table->foreignId('route_id')->constrained('routes');
      $table->text('reason')->nullable();

      // Basic tracking info
      $table->unsignedBigInteger('assigned_by');
      $table->timestamps();

      // Foreign Keys
      $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');

      // Indexes for better performance
      $table->index(['reference_id', 'reference_type']);
      $table->index('job_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('extra_routes');
  }
};
