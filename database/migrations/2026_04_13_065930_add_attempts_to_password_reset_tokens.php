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
        // Check if the column doesn't exist before adding
        if (!Schema::hasColumn('password_reset_tokens', 'attempts')) {
            Schema::table('password_reset_tokens', function (Blueprint $table) {
                $table->integer('attempts')->default(0)->after('token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('password_reset_tokens', 'attempts')) {
            Schema::table('password_reset_tokens', function (Blueprint $table) {
                $table->dropColumn('attempts');
            });
        }
    }
};