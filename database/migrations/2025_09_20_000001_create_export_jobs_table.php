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
    Schema::create('export_jobs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('cro_id')->constrained('cros')->onDelete('cascade');
      $table->string('container')->nullable();

      $table->integer('size')->default(20);
      $table->unsignedBigInteger('line_id');
      $table->unsignedBigInteger('forwarder_id');
      $table->foreignId('pod_id')->constrained('pods')->onDelete('cascade');
      $table->foreignId('terminal_id')->constrained('terminals')->onDelete('cascade');
      $table->unsignedBigInteger('empty_pickup')->nullable();

      $table->enum('status', [
        'Open',
        'In Progress',
        'On Route',
        'Stuck On Port',
        'Vehicle Returned',
        'Empty Return',
        'Completed',
        'Cancelled'
      ])->default('Open');

      $table->timestamps();

      // Foreign Keys
      $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
      $table->foreign('forwarder_id')->references('id')->on('parties')->onDelete('cascade');
      $table->foreign('empty_pickup')->references('id')->on('locations')->restrictOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('export_jobs');
  }
};
