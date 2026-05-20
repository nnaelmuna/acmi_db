<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $industries = ['Software', 'Energi', 'FnB', 'Manufaktur', 'Properti', 'Fintech'];

        for ($i = 1; $i <= 20; $i++) {
            \App\Models\Member::create([
                'name' => 'Member Ke-' . $i,
                'email' => 'member' . $i . '@example.com',
                'phone' => '0812345678' . $i,
                'company_name' => 'PT Sukses Jaya ' . $i,
                'industry' => $industries[array_rand($industries)],
                'position' => 'Manager',
                'status' => 'active'
            ]);
        }
    }
}
