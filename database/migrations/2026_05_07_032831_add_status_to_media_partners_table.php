<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('media_partners', 'status')) {
                $table->string('status', ['published', 'draft', 'archived'])
                ->default('published')
                ->after('link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('media_partners', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
