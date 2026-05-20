<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            // Kasih default 'pending' biar data baru otomatis masuk kategori requested
            $table->string('status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            // Ini buat jaga-jaga kalau mau di-rollback (hapus kolomnya lagi)
            $table->dropColumn('status');
        });
    }
};
