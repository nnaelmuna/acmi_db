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
            if (!Schema::hasColumn('posts', 'title_en')) {
                $table->string('title_en')->nullable()->after('title');
            }
            if (!Schema::hasColumn('posts', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('posts', 'content_en')) {
                $table->longText('content_en')->nullable()->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(
                array_filter(
                    ['title_en', 'description_en', 'content_en'],
                    fn($col) => Schema::hasColumn('posts', $col)
                )
            );
        });
    }
};
