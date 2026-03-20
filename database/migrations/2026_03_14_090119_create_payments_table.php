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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->double('amount');
            $table->string('payment_method');
            $table->string('transaction_reference');

            // fk
            $table->foreignId("status_id")
                ->references("id")
                ->on("statuses")
                ->onDelete("cascade");

            $table->foreignId('request_id')
                ->references("id")
                ->on("requests")
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
