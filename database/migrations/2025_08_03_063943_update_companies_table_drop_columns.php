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
    Schema::table('companies', function (Blueprint $table) {
      $table->dropColumn(['address', 'phone', 'email', 'contact_person', 'ntn']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('companies', function (Blueprint $table) {
      $table->text('address');
      $table->string('phone');
      $table->string('email');
      $table->string('contact_person');
      $table->string('ntn')->unique();
    });
  }
};
