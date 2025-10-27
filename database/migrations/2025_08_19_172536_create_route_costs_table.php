<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('route_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('route_truck_assignments')->onDelete('cascade');
            $table->enum('cost_type', ['fuel','toll','driver_allowance','maintenance','other']);
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->string('receipt_file')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('route_costs');
    }
};
