<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sistem bakal ngecek dulu, kalau kolom 'deleted_at' BELUM ADA, baru dibikin
        if (!Schema::hasColumn('members', 'deleted_at')) {
            Schema::table('members', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
