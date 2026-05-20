<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MediaCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Summit',
            'Roundtable',
            'Masterclass',
            'Mission',
            'Networking',
            'Gala',
        ];

        foreach ($categories as $category) {
            MediaCategory::updateOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                    'slug' => Str::slug($category),
                    'is_default' => 1,
                ]
            );
        }
    }
}
