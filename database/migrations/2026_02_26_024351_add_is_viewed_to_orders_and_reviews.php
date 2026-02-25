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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_viewed')->default(false)->after('status');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->boolean('is_viewed')->default(false)->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_viewed');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('is_viewed');
        });
    }
};
