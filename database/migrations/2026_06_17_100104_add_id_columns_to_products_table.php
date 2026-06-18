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
            if (!Schema::hasColumn('products', 'title_id')) {
                $table->string('title_id')->nullable()->after('title_en');
            }
            if (!Schema::hasColumn('products', 'description_id')) {
                $table->text('description_id')->nullable()->after('description_en');
            }
            if (!Schema::hasColumn('products', 'features_id')) {
                $table->text('features_id')->nullable()->after('features_en');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'title_id')) {
                $table->dropColumn('title_id');
            }

            if (!Schema::hasColumn('products', 'description_id')) {
                $table->dropColumn('description_id');
            }

            if (!Schema::hasColumn('products', 'features_id')) {
                $table->dropColumn('features_id');
            }
        });
    }
};
