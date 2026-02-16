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
        Schema::create('orders', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->string('status')->default('pending');
            $blueprint->decimal('total_amount', 10, 2);
            $blueprint->decimal('discount_amount', 10, 2)->default(0);
            $blueprint->decimal('shipping_amount', 10, 2)->default(0);
            $blueprint->decimal('tax_amount', 10, 2)->default(0);
            
            // Shipping Details
            $blueprint->string('name');
            $blueprint->string('email');
            $blueprint->string('phone');
            $blueprint->string('address');
            $blueprint->string('city');
            $blueprint->string('state')->nullable();
            $blueprint->string('zip_code');
            $blueprint->string('country')->default('Pakistan');
            $blueprint->text('notes')->nullable();
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
