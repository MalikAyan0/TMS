<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('routes', function (Blueprint $table) {
      // Drop the commented out fields from the original migration
      $table->dropColumn([
        'distance',
        'estimated_time',
        'expected_cost'
      ]);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('routes', function (Blueprint $table) {
      // Re-add the columns if the migration is rolled back
      $table->decimal('distance', 10, 2)->after('destination')->nullable();
      $table->integer('estimated_time')->after('distance')->nullable();
      $table->decimal('expected_cost', 12, 2)->after('expected_fuel')->nullable();
    });
  }
};
