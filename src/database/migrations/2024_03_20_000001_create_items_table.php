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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('brand_name')->nullable();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image_path');
            $table->string('status')->default('on_sale');
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor'])->default('new');
            $table->string('category')->nullable(); // カテゴリ情報を直接追加
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
}; 