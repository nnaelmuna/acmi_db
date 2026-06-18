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
                $table->string('title_id')->nullable()->after('title');
            }
            if (!Schema::hasColumn('posts', 'description_id')) {
                $table->text('description_id')->nullable()->after('description');
            }
            if (!Schema::hasColumn('posts', 'content_id')) {
                $table->longText('content_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
    
            if (!Schema::hasColumn('posts', 'title_id')) {
                $table->dropColumn('title_id');
            }
    
            if (!Schema::hasColumn('posts', 'description_id')) {
                $table->dropColumn('description_id');
            }
    
            if (!Schema::hasColumn('posts', 'content_id')) {
                $table->dropColumn('content_id');
            }
    
        });
    }
};
