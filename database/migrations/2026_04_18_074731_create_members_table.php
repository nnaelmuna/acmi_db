<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sesuaikan jadi 'name' biar sama kayak Inbound
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('linkedin_url')->nullable();
            $table->string('company_name');
            $table->string('industry'); // Penting buat filter industri!
            $table->string('position');
            $table->string('company_url')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->softDeletes(); // INI WAJIB biar error deleted_at tadi ilang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
