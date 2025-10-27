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
    Schema::table('fuel_payments', function (Blueprint $table) {
      // Add tank_id column after fleet_id
      $table->unsignedBigInteger('tank_id')->nullable()->after('fleet_id');

      // Add foreign key constraint with restrict on delete
      $table->foreign('tank_id')->references('id')->on('tanks')->onDelete('restrict');

      // Add index for better performance
      $table->index('tank_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('fuel_payments', function (Blueprint $table) {
      // Drop foreign key constraint
      $table->dropForeign(['tank_id']);

      // Drop index
      $table->dropIndex(['tank_id']);

      // Drop column
      $table->dropColumn('tank_id');
    });
  }
};
