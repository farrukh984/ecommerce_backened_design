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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();

            $table->boolean('is_negotiable')->default(false);
            $table->boolean('in_stock')->default(true);
            
            // Specification Fields
            $table->string('type')->nullable();
            $table->string('material')->nullable();
            $table->string('design_style')->nullable();
            $table->string('customization')->nullable();
            $table->string('protection')->nullable();
            $table->string('warranty')->nullable();
            $table->string('model_number')->nullable();
            $table->string('item_number')->nullable();
            $table->string('size')->nullable();
            $table->string('memory')->nullable();
            $table->string('certificate')->nullable();
            $table->string('style')->nullable();

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('condition_id')->references('id')->on('conditions')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
