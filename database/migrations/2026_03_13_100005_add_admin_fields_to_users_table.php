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
        $hasOfficeId = Schema::hasColumn('users', 'office_id');
        $hasRoleId = Schema::hasColumn('users', 'role_id');

        Schema::table('users', function (Blueprint $table) use ($hasTwoFactorAuthentication, $hasOfficeId, $hasRoleId) {
            if (! $hasTwoFactorAuthentication) {
                $table->boolean('two_factor_authentication')->default(false);
            }

            if (! $hasOfficeId) {
                $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            }

            if (! $hasRoleId) {
                $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasTwoFactorAuthentication = Schema::hasColumn('users', 'two_factor_authentication');
        $hasOfficeId = Schema::hasColumn('users', 'office_id');
        $hasRoleId = Schema::hasColumn('users', 'role_id');

        Schema::table('users', function (Blueprint $table) use ($hasTwoFactorAuthentication, $hasOfficeId, $hasRoleId) {
            if ($hasRoleId) {
                $table->dropConstrainedForeignId('role_id');
            }

            if ($hasOfficeId) {
                $table->dropConstrainedForeignId('office_id');
            }

            if ($hasTwoFactorAuthentication) {
                $table->dropColumn('two_factor_authentication');
            }
        });
    }
};
