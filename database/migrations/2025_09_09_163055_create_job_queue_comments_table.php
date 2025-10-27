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
    Schema::create('job_comments', function (Blueprint $table) {
      $table->id();
      $table->enum('type', ['import', 'export']); // Type field to determine if it's an import or export job comment
      $table->unsignedBigInteger('job_id'); // Generic job ID (could be jobs_queue_id or export_jobs_id)
      $table->string('status'); // Status the comment is related to
      $table->text('comment'); // The comment text
      $table->unsignedBigInteger('user_id'); // User who added the comment
      $table->timestamps();

      // Foreign key constraint for user
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

      // We're removing the direct foreign key constraint to jobs_queue
      // since we now handle both import and export jobs
      // The application logic will handle the relationship based on the 'type' field
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('job_comments');
  }
};
