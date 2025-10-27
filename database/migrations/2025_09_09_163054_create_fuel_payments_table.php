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
    Schema::create('fuel_payments', function (Blueprint $table) {
      $table->id();

      // Reference to the related record
      $table->unsignedBigInteger('reference_id');
      $table->enum('reference_type', ['logistics', 'empty_return']);

      // Fleet information
      $table->unsignedBigInteger('fleet_id');

      // Payment status
      $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
      $table->date('payment_date')->nullable();
      $table->text('payment_notes')->nullable();

      $table->timestamps();

      // Foreign key constraints
      $table->foreign('fleet_id')->references('id')->on('fleets')->onDelete('cascade');

      // Indexes for better performance
      $table->index(['reference_id', 'reference_type']);
      $table->index('payment_status');
      $table->index('payment_date');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fuel_payments');
  }
};
