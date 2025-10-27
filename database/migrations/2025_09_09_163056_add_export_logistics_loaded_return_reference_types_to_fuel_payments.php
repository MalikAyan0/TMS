<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include new reference types
        DB::statement("ALTER TABLE fuel_payments MODIFY COLUMN reference_type ENUM('logistics', 'empty_return', 'export_logistics', 'loaded_return')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum back to original values
        DB::statement("ALTER TABLE fuel_payments MODIFY COLUMN reference_type ENUM('logistics', 'empty_return')");
    }
};
