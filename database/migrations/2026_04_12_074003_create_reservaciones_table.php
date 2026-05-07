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
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hoteles')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('habitaciones')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('huespedes')->onDelete('cascade');
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->decimal('total', 8, 2);
            $table->string('status')->default('pending');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};
