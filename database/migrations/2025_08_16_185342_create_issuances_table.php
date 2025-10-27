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
        Schema::create('issuances', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');

             // Created by (user who issued)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            // Other fields
            $table->date('fill_date')->nullable();
            $table->decimal('qty', 10, 2)->default(0);
            $table->string('driver')->nullable();
            $table->bigInteger('odometer_reading')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuances');
    }
};
