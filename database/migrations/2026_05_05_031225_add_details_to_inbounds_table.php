<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            // Cek dulu apakah kolomnya sudah ada sebelum ditambahin
            if (!Schema::hasColumn('inbounds', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('inbounds', 'email')) {
                $table->string('email')->after('name');
            }
            if (!Schema::hasColumn('inbounds', 'company')) {
                $table->string('company')->after('email');
            }
            if (!Schema::hasColumn('inbounds', 'industry')) {
                $table->string('industry')->after('company')->nullable();
            }
            if (!Schema::hasColumn('inbounds', 'message')) {
                $table->text('message')->after('industry');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'company', 'industry', 'message']);
        });
    }
};
