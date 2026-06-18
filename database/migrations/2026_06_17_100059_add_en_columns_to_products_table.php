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
            if (!Schema::hasColumn('products', 'title_en')) {
                $table->string('title_en')->nullable()->after('title');
            }
            if (!Schema::hasColumn('products', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'features_en')) {
                $table->longText('features_en')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): voids
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'title_en')) {
                $table->dropColumn('title_en');
            }

            if (!Schema::hasColumn('products', 'description_en')) {
                $table->dropColumn('description_en');
            }

            if (!Schema::hasColumn('products', 'features_en')) {
                $table->dropColumn('features_en');
            }
        });
    }
};
