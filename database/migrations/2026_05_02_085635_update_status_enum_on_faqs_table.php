<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE faqs MODIFY status ENUM('draft','published','archived') DEFAULT 'published'");
        }
    }

    public function down(): void
    {
        DB::table('faqs')
            ->where('status', 'archived')
            ->update(['status' => 'draft']);

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE faqs MODIFY status ENUM('draft','published') DEFAULT 'published'");
        }
    }
};
