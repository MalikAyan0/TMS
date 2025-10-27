<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('route_truck_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            // $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('expected_end_date')->nullable();
            $table->enum('status', ['assigned','in-progress','completed','delayed'])->default('assigned');

            // auto-filled from route
            $table->decimal('expected_fuel', 10, 2);
            $table->decimal('expected_cost', 12, 2);

            // actuals
            $table->decimal('actual_fuel_used', 10, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('route_truck_assignments');
    }
};
