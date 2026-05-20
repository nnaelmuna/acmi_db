<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('instagram_posts')) {
            Schema::create('instagram_posts', function (Blueprint $table) {
                $table->id();
                $table->string('instagram_id')->unique();
                $table->string('image_url')->nullable();
                $table->string('local_image_path')->nullable();
                $table->string('permalink')->nullable();
                $table->text('caption')->nullable();
                $table->integer('likes')->default(0);
                $table->integer('comments')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_posts');
    }
};