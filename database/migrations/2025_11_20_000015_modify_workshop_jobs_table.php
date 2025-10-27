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
    Schema::table('workshop_jobs', function (Blueprint $table) {
      $table->dropColumn(['paid_by_terminal', 'port']);
      $table->renameColumn('price', 'reconciliation');
      $table->renameColumn('parts', 'parts_detail');
      $table->text('description')->nullable();
      $table->dropColumn(['kict', 'byweste']);
      $table->enum('type', ['kict', 'byweste'])->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('workshop_jobs', function (Blueprint $table) {
      $table->dropColumn(['description', 'type']);
      $table->renameColumn('parts_detail', 'parts');
      $table->renameColumn('reconciliation', 'price');
      $table->string('paid_by_terminal')->nullable();
      $table->string('port')->nullable(false);
      $table->string('kict')->nullable(false);
      $table->string('byweste')->nullable(false);
    });
  }
};
