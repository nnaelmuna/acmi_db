<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        // Cek dulu biar aman, kalau kolom 'status' belum ada di tabel media_partners
        if (!Schema::hasColumn('media_partners', 'status')) {
            Schema::table('media_partners', function (Blueprint $table) {
                // Dibikin string biasa dengan nilai bawaan 'pending' (atau 'review')
                $table->string('status')->default('review'); 
            });
        }
    }

    public function down(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('media_partners', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
