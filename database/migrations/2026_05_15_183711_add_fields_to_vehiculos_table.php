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
    Schema::table('vehiculos', function (Blueprint $table) {
        $table->string('tipo')->default('auto')->after('color');
        // tipo: auto, moto, camioneta, bus
        $table->decimal('tarifa_por_hora', 8, 2)->default(0)->after('tipo');
        $table->decimal('total_cobrado', 8, 2)->default(0)->after('tarifa_por_hora');
    });
}

public function down(): void
{
    Schema::table('vehiculos', function (Blueprint $table) {
        $table->dropColumn(['tipo', 'tarifa_por_hora', 'total_cobrado']);
    });
}
};
