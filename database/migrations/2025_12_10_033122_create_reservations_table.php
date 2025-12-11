<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained('guests')->nullOnDelete();
            $table->date('checkin_at');
            $table->date('checkout_at');
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['booked','checked_in','checked_out','cancelled'])->default('booked');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reservations');
    }
};
