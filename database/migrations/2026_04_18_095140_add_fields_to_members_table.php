<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('mobile')->nullable();
            $table->string('company')->nullable();
            $table->string('industry')->nullable();
            $table->string('position')->nullable();
            $table->string('company_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
