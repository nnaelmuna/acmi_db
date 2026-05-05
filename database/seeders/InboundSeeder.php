<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InboundSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Inbound::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'company' => 'PT Teknologi Jaya',
            'industry' => 'Technology',
            'message' => 'Halo, mau tanya harga.',
            'status' => 'requested',
        ]);
    
        \App\Models\Inbound::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@web.com',
            'company' => 'Catering Sejahtera',
            'industry' => 'Food & Beverage',
            'message' => 'Kerjasama catering kantor.',
            'status' => 'approved',
        ]);
    
        \App\Models\Inbound::create([
            'name' => 'John Doe',
            'email' => 'john@corp.com',
            'company' => 'Global Corp',
            'industry' => 'Manufacturing',
            'message' => 'Inquiry for heavy machinery.',
            'status' => 'review',
        ]);
    }


}
