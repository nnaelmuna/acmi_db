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
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'question_id')) {
                $table->string('question_id')->nullable()->after('question_en');
            }
            if (!Schema::hasColumn('faqs', 'answer_id')) {
                $table->text('answer_id')->nullable()->after('answer_en');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'question_id')) {
                $table->dropColumn('question_id');
            }
            if (!Schema::hasColumn('faqs', 'answer_id')) {
                $table->dropColumn('answer_id');
            }
        });
    }
};
