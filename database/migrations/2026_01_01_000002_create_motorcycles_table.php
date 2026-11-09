<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 50);
            $table->string('model_name', 100);
            $table->string('production_year', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
