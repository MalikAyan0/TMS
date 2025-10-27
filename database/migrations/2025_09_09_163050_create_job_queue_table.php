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
    Schema::create('jobs_queue', function (Blueprint $table) {
      $table->id();
      $table->string('job_number')->unique()->index();
      $table->foreignId('bail_number_id')->constrained('bail')->onDelete('cascade');
      $table->string('container')->nullable();
      $table->unsignedBigInteger('company_id');
      $table->integer('size')->default(20);
      $table->unsignedBigInteger('line_id');
      $table->unsignedBigInteger('forwarder_id');
      $table->unsignedBigInteger('port_id');
      $table->date('noc_deadline')->nullable();
      $table->date('eta')->nullable();

      $table->enum('mode', [
        'CFS',
        'CY'
      ])->default('CFS');

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
      $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
      $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
      $table->foreign('forwarder_id')->references('id')->on('parties')->onDelete('cascade');
      $table->foreign('port_id')->references('id')->on('ports')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('jobs_queue');
  }
};
