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
        // MySQL requires an index where cart_id is leftmost to back the FK before we drop the composite unique
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id', 'cart_items_cart_id_tmp');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'product_id']);
            $table->foreignId('variant_id')->nullable()->after('product_id')
                ->constrained('product_variants')->nullOnDelete();
            $table->unique(['cart_id', 'product_id', 'variant_id']);
        });

        // Remove temp index — the new composite unique now backs the cart_id FK
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('cart_items_cart_id_tmp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id', 'cart_items_cart_id_tmp');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'product_id', 'variant_id']);
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
            $table->unique(['cart_id', 'product_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('cart_items_cart_id_tmp');
        });
    }
};
