<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('route_stops', function (Blueprint $table) {
      $table->id();
      $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
      $table->integer('stop_order');
      $table->string('location_name');
      $table->decimal('load', 10, 2)->nullable();
      $table->string('load_type')->nullable();
      $table->dateTime('arrival_time')->nullable();
      $table->dateTime('departure_time')->nullable();
      $table->dateTime('actual_arrival_time')->nullable();
      $table->dateTime('actual_departure_time')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('route_stops');
  }
};
