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
        Schema::create('sales_tax_territories', function (Blueprint $table) {
            $table->id();
            $table->string('head');
            $table->string('title');
            $table->string('short_name', 15)->unique();
            $table->text('address');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Add indexes for better performance
            $table->index('status');
            $table->index('short_name');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_tax_territories');
    }
};
