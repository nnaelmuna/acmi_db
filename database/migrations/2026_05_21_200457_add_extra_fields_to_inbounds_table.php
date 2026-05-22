<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            $table->string('linkedin_url')->nullable()->after('company_url');
            $table->string('employee_size')->nullable()->after('industry'); 
            $table->string('annual_revenue')->nullable()->after('employee_size');
            $table->text('motivation_referral')->nullable()->after('annual_revenue');
        });
    }

    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            // Untuk rollback jika diperlukan
            $table->dropColumn(['linkedin_url', 'employee_size', 'annual_revenue', 'motivation_referral']);
        });
    }
};