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
        if (! Schema::hasColumn('municipalities', 'address')) {
            Schema::table('municipalities', function (Blueprint $table) {
                $table->string('address')->nullable()->after('region');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('municipalities', 'address')) {
            Schema::table('municipalities', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
    }
};
