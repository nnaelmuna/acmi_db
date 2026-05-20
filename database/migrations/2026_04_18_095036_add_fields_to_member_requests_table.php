<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_requests', function (Blueprint $table) {
            $table->string('mobile')->nullable();
            $table->string('company')->nullable();
            $table->string('industry')->nullable();
            $table->string('position')->nullable();
            $table->string('company_url')->nullable();
            $table->enum('status', ['review', 'approved', 'rejected'])->default('review')->change();
        });
    }

    public function down(): void
    {
        Schema::table('member_requests', function (Blueprint $table) {
            //
        });
    }
};
