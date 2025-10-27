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
    Schema::table('extra_routes', function (Blueprint $table) {
      $table->unsignedBigInteger('vehicle_id')->nullable()->after('route_id');
      $table->foreign('vehicle_id')->references('id')->on('fleets')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('extra_routes', function (Blueprint $table) {
      $table->dropForeign(['vehicle_id']);
      $table->dropColumn('vehicle_id');
    });
  }
};
