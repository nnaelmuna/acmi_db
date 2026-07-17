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
            // Informasi Pribadi
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('birth_date');
            $table->string('domicile')->nullable()->after('gender');
            $table->text('address')->nullable()->after('domicile');
            $table->string('shirt_size')->nullable()->after('address');

            // Informasi Bisnis
            $table->text('company_address')->nullable()->after('company_url');
            $table->text('business_detail')->nullable()->after('company_address');

            // Akun Media Sosial
            $table->string('instagram')->nullable()->after('linkedin_url');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('facebook')->nullable()->after('tiktok');

            // Motivasi & Referral
            $table->string('ceo_mm_batch')->nullable()->after('motivation_referral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbounds', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'gender',
                'domicile',
                'address',
                'shirt_size',
                'company_address',
                'business_detail',
                'instagram',
                'tiktok',
                'facebook',
                'ceo_mm_batch',
            ]);
        });
    }
};
