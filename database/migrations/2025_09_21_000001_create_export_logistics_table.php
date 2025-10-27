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
    Schema::create('export_logistics', function (Blueprint $table) {
      $table->id();
      $table->foreignId('export_job_id')->constrained('export_jobs')->onDelete('cascade');

      $table->enum('market_vehicle', ['yes', 'no'])->default('no');
      $table->string('market_vehicle_details', 255)->nullable();
      $table->unsignedBigInteger('vehicle_id')->nullable();
      $table->string('gate_pass')->nullable();
      $table->timestamp('gate_time_passed')->nullable();
      $table->foreignId('route_id')->nullable()->constrained();

      $table->timestamps();

      // Foreign Keys
      $table->foreign('vehicle_id')->references('id')->on('fleets')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('export_logistics');
  }
};
