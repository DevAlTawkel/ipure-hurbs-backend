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
            $table->json('supplement_facts')->nullable()->after('indications');
            $table->json('suggested_use')->nullable()->after('supplement_facts');
            $table->json('warnings')->nullable()->after('suggested_use');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['supplement_facts', 'suggested_use', 'warnings']);
        });
    }
};
