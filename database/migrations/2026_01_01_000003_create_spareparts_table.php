<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('part_code', 50)->unique();
            $table->string('name', 255);
            $table->string('brand', 100);
            $table->integer('price');
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('sparepart_motorcycle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_id')->constrained('spareparts')->cascadeOnDelete();
            $table->foreignId('motorcycle_id')->constrained('motorcycles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sparepart_motorcycle');
        Schema::dropIfExists('spareparts');
    }
};
