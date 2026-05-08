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
        Schema::table('users', function (Blueprint $table) {
            $table->string('rfc_id')->nullable()->after('email'); // RFC o ID del cliente
            $table->string('phone')->nullable()->after('rfc_id'); // Teléfono
            $table->text('address')->nullable()->after('phone'); // Dirección
            $table->string('client_code')->nullable()->unique()->after('address'); // Código de cliente único
            $table->enum('status', ['active', 'inactive', 'blacklist'])->default('active')->after('client_code'); // Estado: activo, inactivo, lista negra
            $table->enum('category', ['minorista', 'mayorista', 'vip'])->default('minorista')->after('status'); // Categoría
            $table->decimal('credit_limit', 12, 2)->default(0)->after('category'); // Límite de crédito
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'rfc_id',
                'phone',
                'address',
                'client_code',
                'status',
                'category',
                'credit_limit',
            ]);
        });
    }
};
