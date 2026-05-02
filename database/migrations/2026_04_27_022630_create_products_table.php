<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('title');
            $table->string('company_name');
            $table->string('ceo_name');
            $table->text('description');
            $table->json('features')->nullable(); // Pastikan ini cuma satu
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }
};
