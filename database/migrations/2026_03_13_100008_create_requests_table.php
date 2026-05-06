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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            $table->string('qr_code')->nullable();
            $table->string('tracking_number');
            

            // fk
            $table->foreignId("user_id")
                ->nullable()
                ->references("id")
                ->on("users")
                ->nullOnDelete();
                
            $table->foreignId("status_id")
                ->references("id")
                ->on("statuses")
                ->onDelete("cascade");

            $table->foreignId("service_id")
                ->references("id")
                ->on("services")
                ->onDelete("cascade");

            $table->text('status_note')->nullable();
            $table->foreignId("reviewed_by")
                ->nullable()
                ->references("id")
                ->on("users")
                ->nullOnDelete();

            /*
            $table->foreignId("appointment_id")
                ->references("id")
                ->on("appointments")
                ->onDelete("cascade");
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
