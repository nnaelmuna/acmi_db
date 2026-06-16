<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        // Cek dulu, kalau belum ada baru buat. Kalau udah ada, aman dilewati!
        if (!Schema::hasColumn('media_categories', 'deleted_at')) {
            Schema::table('media_categories', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('media_categories', 'deleted_at')) {
            Schema::table('media_categories', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};