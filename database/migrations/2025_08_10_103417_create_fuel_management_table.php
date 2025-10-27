<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelManagementTable extends Migration
{
    public function up()
    {
        Schema::create('fuel_management', function (Blueprint $table) {
            $table->id();

            // Vendor (stored in parties table with party_type = 'vendor')
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')
                ->references('id')
                ->on('parties')
                ->onDelete('cascade');

            // Fuel type
            $table->unsignedBigInteger('fuel_type_id');
            $table->foreign('fuel_type_id')
                ->references('id')
                ->on('fuel_types')
                ->onDelete('cascade');

            // Tank
            $table->unsignedBigInteger('tank_id');
            $table->foreign('tank_id')
                ->references('id')
                ->on('tanks')
                ->onDelete('cascade');

            $table->decimal('qty', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->decimal('amount', 12, 2);
            $table->date('delivery_date')->nullable();
            $table->decimal('freight_charges', 10, 2)->nullable();

            // Remarks field for additional notes or comments
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fuel_management');
    }
}
