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
        Schema::table('products', function (Blueprint $table) {
            // Drop old condition column if it exists and add condition_id
            if (Schema::hasColumn('products', 'condition')) {
                $table->dropColumn('condition');
            }
            if (!Schema::hasColumn('products', 'condition_id')) {
                $table->unsignedBigInteger('condition_id')->nullable()->after('features');
                $table->foreign('condition_id')->references('id')->on('conditions')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'condition_id')) {
                $table->dropForeign(['condition_id']);
                $table->dropColumn('condition_id');
            }
        });
    }
};
