<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Juri;

class JuriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $juris = [
            [
                'nama_lengkap' => 'Ustadz Ahmad Yusuf, Lc.',
                'email' => 'ahmad.yusuf@example.com',
                'keahlian' => 'Tajwid dan Qiraat',
                'institusi' => 'Pondok Pesantren Al-Hikmah',
                'kontak' => '+62812345678',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'Ustadzah Fatimah Zahra, S.Pd.I.',
                'email' => 'fatimah.zahra@example.com',
                'keahlian' => 'Fasohah dan Tartil',
                'institusi' => 'Madrasah Aliyah Negeri 1 Jakarta',
                'kontak' => '+62823456789',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'Ustadz Muhammad Rizqi, M.Q.',
                'email' => 'muhammad.rizqi@example.com',
                'keahlian' => 'Ilmu Tajwid dan Makhraj',
                'institusi' => 'Institut Ilmu Al-Qur\'an Jakarta',
                'kontak' => '+62834567890',
                'is_active' => true,
            ],
        ];

        foreach ($juris as $j) {
            Juri::create($j);
        }

        $this->command->info('✓ Data Juri berhasil ditambahkan!');
    }
}
