<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Pricing
            $table->decimal('sale_price', 10, 2)->nullable()->after('compare_price');
            
            // Inventory Management
            $table->unsignedInteger('low_stock_threshold')->default(10)->after('stock');
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock')->after('low_stock_threshold');
            $table->string('barcode')->nullable()->unique()->after('sku');
            
            // SEO Fields
            $table->string('seo_title')->nullable()->after('is_trending');
            $table->text('seo_description')->nullable()->after('seo_title');
            
            // Sales Tracking
            $table->unsignedInteger('sales_count')->default(0)->after('review_count');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sale_price',
                'low_stock_threshold',
                'stock_status',
                'barcode',
                'seo_title',
                'seo_description',
                'sales_count'
            ]);
        });
    }
};
