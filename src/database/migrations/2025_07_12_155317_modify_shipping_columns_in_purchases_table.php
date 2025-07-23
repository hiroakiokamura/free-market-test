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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('shipping_postal_code')->nullable()->change();
            $table->string('shipping_prefecture')->nullable()->change();
            $table->string('shipping_city')->nullable()->change();
            $table->string('shipping_address')->nullable()->change();
            $table->string('shipping_building')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('shipping_postal_code')->nullable(false)->change();
            $table->string('shipping_prefecture')->nullable(false)->change();
            $table->string('shipping_city')->nullable(false)->change();
            $table->string('shipping_address')->nullable(false)->change();
            $table->string('shipping_building')->nullable(false)->change();
        });
    }
};
