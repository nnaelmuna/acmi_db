<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'company_name')) {
                $table->string('company_name')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
