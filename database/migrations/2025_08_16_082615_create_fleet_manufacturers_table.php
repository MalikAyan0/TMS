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
        Schema::create('fleet_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Manufacturer name
            $table->string('image')->nullable(); // Logo or image
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Who created
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_manufacturers');
    }
};
