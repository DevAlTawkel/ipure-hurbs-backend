<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('logo')->nullable()->after('description');
            $table->string('website')->nullable()->after('logo');
            $table->boolean('is_active')->default(true)->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['description', 'logo', 'website', 'is_active']);
        });
    }
};
