<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('media_partners', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('media_partner', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
