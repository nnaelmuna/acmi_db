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
        Schema::table('inbounds', function (Blueprint $table) {
            // Kita tambahin kolom status setelah kolom ID ya
            $table->string('status')->default('requested')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            // Ini buat jaga-jaga kalau mau di-rollback (hapus kolomnya lagi)
            $table->dropColumn('status');
        });
    }
};
