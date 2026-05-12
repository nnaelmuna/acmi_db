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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Kita tambahin kolom user_id setelah kolom description
            // nullable() supaya kalau ada sistem yang gak pake login tetap bisa jalan
            $table->unsignedBigInteger('user_id')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Kalau di-rollback, hapus kolomnya
            $table->dropColumn('user_id');
        });
    }
};