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
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('fleet_manufacturer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fleet_type_id')->constrained()->cascadeOnDelete();

            // Fleet details
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('model')->nullable();
            $table->string('horsepower')->nullable();
            $table->string('loading_capacity')->nullable();
            $table->string('registration_city')->nullable();
            $table->string('ownership')->nullable();
            $table->string('lifting_capacity')->nullable();
            $table->decimal('diesel_opening_inventory', 10, 2)->default(0);

            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
