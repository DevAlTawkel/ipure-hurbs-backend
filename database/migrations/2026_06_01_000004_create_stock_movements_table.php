<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('movement_type', ['purchase', 'return', 'adjustment', 'damaged', 'lost'])->default('purchase');
            $table->integer('quantity'); // Positive for inflow, negative for outflow
            $table->string('reference')->nullable(); // Order ID, Return ID, etc.
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // Admin user ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
