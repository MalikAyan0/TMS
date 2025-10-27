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
    // Drop the old table
    Schema::dropIfExists('job_queue_comments');

    // Create the new table
    Schema::create('job_comments', function (Blueprint $table) {
      $table->id();
      $table->enum('type', ['import', 'export']);
      $table->unsignedBigInteger('job_id');
      $table->string('status');
      $table->text('comment');
      $table->unsignedBigInteger('user_id');
      $table->timestamps();

      // Foreign key constraint for user
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Drop the new table
    Schema::dropIfExists('job_comments');

    // Recreate the old table structure
    Schema::create('job_queue_comments', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('jobs_queue_id');
      $table->string('status');
      $table->text('comment');
      $table->unsignedBigInteger('user_id');
      $table->timestamps();

      // Foreign key constraints
      $table->foreign('jobs_queue_id')->references('id')->on('jobs_queue')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }
};
