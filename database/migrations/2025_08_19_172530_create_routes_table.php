<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('routes', function (Blueprint $table) {
      $table->id();
      $table->string('route_name');
      $table->string('route_code')->unique();
      $table->string('origin');
      $table->string('destination');
      $table->decimal('distance', 10, 2);
      $table->integer('estimated_time');
      $table->decimal('expected_fuel', 10, 2);
      $table->decimal('expected_cost', 12, 2);
      $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
      $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
      $table->timestamps();
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('routes');
  }
};
