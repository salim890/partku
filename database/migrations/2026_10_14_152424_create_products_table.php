<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('part_number')->nullable();
            $table->string('category');
            $table->string('motor_models')->nullable();
            $table->integer('price');
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};