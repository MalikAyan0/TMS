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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->enum('party_type', ['customer', 'vendor']);
            $table->string('title');
            $table->string('short_name', 15)->unique();
            $table->text('address');
            $table->string('contact');
            $table->string('email')->unique();
            $table->string('contact_person');
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('ntn')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['party_type', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
