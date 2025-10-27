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
    Schema::table('export_logistics', function (Blueprint $table) {
      $table->boolean('has_extra_routes')->default(false)
        ->after('route_id')
        ->comment('Flag indicating if this logistics has extra routes assigned');
    });

    Schema::table('job_empty_returns', function (Blueprint $table) {
      $table->boolean('has_extra_routes')->default(false)
        ->after('route_id')
        ->comment('Flag indicating if this empty return has extra routes assigned');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('export_logistics', function (Blueprint $table) {
      $table->dropColumn('has_extra_routes');
    });

    Schema::table('job_empty_returns', function (Blueprint $table) {
      $table->dropColumn('has_extra_routes');
    });
  }
};
