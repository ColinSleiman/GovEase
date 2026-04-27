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
        Schema::create('user_requests', function (Blueprint $table) {

            // fk
            $table->foreignId("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->foreignId("request_id")
                ->references("id")
                ->on("requests")
                ->onDelete("cascade");

            $table->timestamps();

            $table->primary(['user_id', 'request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_requests');
    }
};
