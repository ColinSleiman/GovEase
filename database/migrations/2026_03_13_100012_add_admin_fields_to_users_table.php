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
        $hasTwoFactorAuthentication = Schema::hasColumn('users', 'two_factor_authentication');

        Schema::table('users', function (Blueprint $table) use ($hasTwoFactorAuthentication) {
            if (! $hasTwoFactorAuthentication) {
                $table->boolean('two_factor_authentication')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasTwoFactorAuthentication = Schema::hasColumn('users', 'two_factor_authentication');

        Schema::table('users', function (Blueprint $table) use ($hasTwoFactorAuthentication) {
            if ($hasTwoFactorAuthentication) {
                $table->dropColumn('two_factor_authentication');
            }
        });
    }
};
