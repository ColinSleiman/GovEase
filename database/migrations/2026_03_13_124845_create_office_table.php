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
        Schema::create('office', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('google_maps_location')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('contact_info')->nullable();
            $table->unsignedBigInteger('municipality_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office');
    }
};
