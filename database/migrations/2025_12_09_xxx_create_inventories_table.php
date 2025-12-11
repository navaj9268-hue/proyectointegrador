<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->string('item');
            $table->integer('quantity')->default(0);
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
