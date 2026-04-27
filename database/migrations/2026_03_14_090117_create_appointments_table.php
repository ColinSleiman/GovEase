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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->date('appointment_date');
            $table->time('appointment_time');

            // fk
            $table->foreignId("status_id")
                ->references("id")
                ->on("statuses")
                ->onDelete("cascade");

            $table->foreignId("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->foreignId("office_id")
                ->references("id")
                ->on("offices")
                ->onDelete("cascade");

            $table->foreignId("service_id")
                ->references("id")
                ->on("services")
                ->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
