<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'phone')) {
                $table->string('phone')->nullable()->change();
            }
            if (!Schema::hasColumn('members', 'employee_size')) {
                $table->string('employee_size')->nullable();
            }
            if (!Schema::hasColumn('members', 'annual_revenue')) {
                $table->string('annual_revenue')->nullable();
            }
            if (!Schema::hasColumn('members', 'message')) {
                $table->text('message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $columns = ['employee_size', 'annual_revenue', 'message'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('members', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
