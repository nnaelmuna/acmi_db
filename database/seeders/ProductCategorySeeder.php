<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Software', 'Energi', 'FnB', 'Manufaktur', 
            'Properti', 'Fintech', 'Logistik', 'Health'
        ];

        foreach ($categories as $cat) {
            ProductCategory::create([
                'name' => $cat
            ]);
        }
    }
}
