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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('company_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('position')->nullable();
            $table->string('company_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            
            $table->softDeletes(); // Wajib ada karena modelmu pakai SoftDeletes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};