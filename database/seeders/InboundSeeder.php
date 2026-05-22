<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inbound;

class InboundSeeder extends Seeder
{
    public function run(): void
    {
        Inbound::truncate();

        Inbound::create([
            'name' => 'Nisrina Design',
            'email' => 'nisrina@test.com',
            'phone' => '081234567890',
            'company' => 'Creative Studio',
            'position' => 'UI/UX Designer',
            'industry' => 'UI/UX Design',
            'company_url' => 'https://nisrina.design',
            'employee_size' => '11 - 50 Karyawan',
            'annual_revenue' => 'Rp 500 Juta - 1 Miliar',
            'message' => 'Halo admin, saya tertarik join ecosystem.',
            'motivation_referral' => 'Ingin bergabung karena tertarik dengan ekosistem CRM ACMI yang sangat modern.',
            'status' => 'review',
        ]);
    }
}