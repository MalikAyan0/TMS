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
    Schema::table('fleets', function (Blueprint $table) {
      // Drop columns that are not in the new specification
      $table->dropColumn([
        'chassis_number',
        'engine_number',
        'model',
        'horsepower',
        'loading_capacity',
        'lifting_capacity'
      ]);

      // Add driver name fields as text
      $table->string('first_driver')->nullable()->after('fleet_type_id');
      $table->string('second_driver')->nullable()->after('first_driver');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('fleets', function (Blueprint $table) {
      // Drop the added columns
      $table->dropColumn(['first_driver', 'second_driver']);

      // Add back the dropped columns
      $table->string('chassis_number')->nullable();
      $table->string('engine_number')->nullable();
      $table->string('model')->nullable();
      $table->string('horsepower')->nullable();
      $table->string('loading_capacity')->nullable();
      $table->string('lifting_capacity')->nullable();
    });
  }
};
