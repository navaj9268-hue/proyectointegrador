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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservaciones')->onDelete('cascade');
            $table->string('placa')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->string('color');
            $table->string('status')->default('parked');
            $table->datetime('fecha_entrada');
            $table->datetime('fecha_salida')->nullable();
            $table->string('lugar_estacionamiento');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
