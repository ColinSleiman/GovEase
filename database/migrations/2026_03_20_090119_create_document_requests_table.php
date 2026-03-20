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
        Schema::create('document_requests', function (Blueprint $table) {

            // fk
            $table->foreignId("request_id")
                ->references("id")
                ->on("requests")
                ->onDelete("cascade");

            $table->foreignId("document_id")
                ->references("id")
                ->on("documents")
                ->onDelete("cascade");

            $table->timestamps();

            $table->primary(['request_id', 'document_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
