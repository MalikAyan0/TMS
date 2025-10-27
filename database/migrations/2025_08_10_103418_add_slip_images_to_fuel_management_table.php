<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlipImagesToFuelManagementTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('fuel_management', function (Blueprint $table) {
      // Change to a string column for a single image path
      $table->string('slip_image')->nullable()->after('remarks')
        ->comment('File path for receipt/slip image');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('fuel_management', function (Blueprint $table) {
      $table->dropColumn('slip_image');
    });
  }
}
