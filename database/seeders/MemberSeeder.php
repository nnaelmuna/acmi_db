<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $industries = ['Software', 'Energi', 'FnB', 'Manufaktur', 'Properti', 'Fintech'];

        for ($i = 1; $i <= 20; $i++) {
            Member::create([
                'name'         => 'Member Ke-' . $i,
                'email'        => 'member' . $i . '@example.com',
                'phone'        => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'company_name' => 'PT Sukses Jaya ' . $i,
                'industry'     => $industries[array_rand($industries)],
                'position'     => 'Manager',
                'company_url'  => 'https://www.suksesjaya' . $i . '.com',
                'linkedin_url' => 'https://linkedin.com/in/member-' . $i,
                'status'       => 'active'
            ]);
        }
    }
}