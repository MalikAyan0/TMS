<?php

use App\Models\Invoice;
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
    Schema::create('workshop_jobs', function (Blueprint $table) {
      $table->id();
      $table->string('parts')->nullable(false); // Required field
      $table->string('invoice')->nullable(false); // Required field
      $table->unsignedBigInteger('vehicle_id'); // Assuming foreign key to vehicles/fleets table
      $table->decimal('price', 10, 2); // Price with 2 decimal places
      $table->string('location');
      $table->string('vendor_name');
      $table->string('paid_by_terminal')->nullable();
      $table->string('port')->nullable(false); // Required field
      $table->string('kict')->nullable(false); // Required field
      $table->string('byweste')->nullable(false); // Required field
      $table->string('slip_image')->nullable(); // New field for slip image
      $table->enum('status', ['requested', 'approved', 'paid', 'rejected'])->default('requested');
      $table->timestamps();

      // Foreign key constraint (adjust table name if needed, e.g., 'fleets' or 'vehicles')
      $table->foreign('vehicle_id')->references('id')->on('fleets')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('workshop_jobs');
  }
};
