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
            if (!Schema::hasColumn('posts', 'slug_id')) {
                $table->string('slug_id')->nullable()->after('slug');
            }
     
            if (!Schema::hasColumn('posts', 'slug_en')) {
                $table->string('slug_en')->nullable()->after('slug_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['slug_id', 'slug_en']);
        });
    }
};
