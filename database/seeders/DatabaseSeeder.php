<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        $this->call([
            SuperAdminSeeder::class,
            CategorySeeder::class,
            MediaCategorySeeder::class,
            ProductCategorySeeder::class,
            InboundSeeder::class
        ]);
    }
}
