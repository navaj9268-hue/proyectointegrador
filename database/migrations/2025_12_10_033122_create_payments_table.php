<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->nullOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('method')->nullable(); // Ej: 'efectivo', 'tarjeta', 'transferencia'
            $table->string('transaction_id')->nullable();
            $table->string('payer_name')->nullable(); // quien pagó
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quien registró el pago
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
