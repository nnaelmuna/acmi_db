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
        Schema::table('posts', function (Blueprint $table) {

            if (!Schema::hasColumn('posts', 'title_id')) {
                $table->string('title_id')->nullable()->after('title_en');
            }

            if (!Schema::hasColumn('posts', 'description_id')) {
                $table->text('description_id')->nullable()->after('description_en');
            }

            if (!Schema::hasColumn('posts', 'content_id')) {
                $table->longText('content_id')->nullable()->after('content_en');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $columns = array_filter([
                'title_id',
                'description_id',
                'content_id',
            ], fn($col) => Schema::hasColumn('posts', $col));

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};