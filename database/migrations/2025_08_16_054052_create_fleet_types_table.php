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
        Schema::create('fleet_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // Fleet type title
            $table->string('image')->nullable();     // Fleet type image (stored path)
            $table->foreignId('created_by')           // User who created the record
              ->nullable()
              ->constrained('users')
              ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_types');
    }
};
