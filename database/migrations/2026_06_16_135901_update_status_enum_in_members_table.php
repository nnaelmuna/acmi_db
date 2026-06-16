<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom status dari ENUM lama ke VARCHAR agar fleksibel
     * untuk mendukung value: active, published, draft, archived, inactive, suspended
     */
    public function up(): void
    {
        // Ubah ENUM ke VARCHAR agar bisa menampung semua status
        DB::statement("ALTER TABLE `members` MODIFY `status` VARCHAR(20) NOT NULL DEFAULT 'active'");

        // Konversi data existing: active → published (agar konsisten dengan MemberController)
        DB::statement("UPDATE `members` SET `status` = 'published' WHERE `status` = 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan published → active
        DB::statement("UPDATE `members` SET `status` = 'active' WHERE `status` = 'published'");

        // Kembalikan ke ENUM
        DB::statement("ALTER TABLE `members` MODIFY `status` ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active'");
    }
};
