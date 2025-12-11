<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->string('number');
            $table->string('type')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['available','occupied','maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
