<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number', 50)->unique();
            $table->enum('order_type', ['beli_saja', 'pasang_bengkel'])->default('beli_saja');
            $table->date('booking_date')->nullable();
            $table->integer('service_fee')->default(0);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'batal'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
