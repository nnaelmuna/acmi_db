<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbounds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('company'); // Di scopeSearch lu tulis company_name, mending samain jadi 'company' aja
            $table->string('industry')->nullable(); // Tambahin ini biar fitur Search lu gak error
            $table->text('message');
            $table->string('status')->default('requested'); // Sesuaikan default-nya (requested/pending)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbounds');
    }
};
