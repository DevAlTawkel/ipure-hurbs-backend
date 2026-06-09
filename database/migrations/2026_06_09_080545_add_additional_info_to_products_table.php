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
            $table->json('key_herbal_ingredients')->nullable()->after('gallery');
            $table->json('key_benefits')->nullable()->after('key_herbal_ingredients');
            $table->json('specifications')->nullable()->after('key_benefits');
            $table->json('indications')->nullable()->after('specifications');
            $table->text('allergen_info')->nullable()->after('indications');
            $table->text('other_ingredients')->nullable()->after('allergen_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'key_herbal_ingredients',
                'key_benefits',
                'specifications',
                'indications',
                'allergen_info',
                'other_ingredients',
            ]);
        });
    }
};
