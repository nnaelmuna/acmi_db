<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('members')) {
            Schema::table('members', function (Blueprint $table) {
                // Cek 'full_name' sebelum rename
                if (Schema::hasColumn('members', 'full_name')) {
                    $table->renameColumn('full_name', 'name');
                }

                // Gunakan if (!Schema::hasColumn) untuk setiap kolom baru
                if (!Schema::hasColumn('members', 'linkedin_url')) {
                    $table->string('linkedin_url')->nullable()->after('phone');
                }

                if (!Schema::hasColumn('members', 'company_name')) {
                    $table->string('company_name')->after('linkedin_url');
                }

                if (!Schema::hasColumn('members', 'industry')) {
                    $table->string('industry')->after('company_name');
                }

                if (!Schema::hasColumn('members', 'position')) {
                    $table->string('position')->after('industry');
                }

                if (!Schema::hasColumn('members', 'company_url')) {
                    $table->string('company_url')->nullable()->after('position');
                }

                if (!Schema::hasColumn('members', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }
    
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
