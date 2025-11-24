<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriteria = [
            [
                'nama_kriteria' => 'Tajwid',
                'deskripsi' => 'Ketepatan dalam membaca huruf hijaiyah, makhrajul huruf, dan hukum tajwid',
                'bobot' => 30.0,
                'bobot_borda' => 1.0,
                'nilai_max' => 100,
                'nilai_min' => 0,
                'atribut' => 'benefit',
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Kelancaran',
                'deskripsi' => 'Kelancaran dan kefasihan dalam membaca Al-Qur\'an tanpa terhenti yang tidak semestinya',
                'bobot' => 25.0,
                'bobot_borda' => 1.0,
                'nilai_max' => 100,
                'nilai_min' => 0,
                'atribut' => 'benefit',
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Fasohah',
                'deskripsi' => 'Kefasihan dan keindangan suara dalam membaca Al-Qur\'an',
                'bobot' => 20.0,
                'bobot_borda' => 1.0,
                'nilai_max' => 100,
                'nilai_min' => 0,
                'atribut' => 'benefit',
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Adab',
                'deskripsi' => 'Etika dan sopan santun saat membaca Al-Qur\'an (cara duduk, pakaian, dll)',
                'bobot' => 15.0,
                'bobot_borda' => 1.0,
                'nilai_max' => 100,
                'nilai_min' => 0,
                'atribut' => 'benefit',
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Tartil',
                'deskripsi' => 'Membaca Al-Qur\'an dengan tartil (pelan-pelan dan tafahhum/mengerti)',
                'bobot' => 10.0,
                'bobot_borda' => 1.0,
                'nilai_max' => 100,
                'nilai_min' => 0,
                'atribut' => 'benefit',
                'is_active' => true,
            ],
        ];

        foreach ($kriteria as $k) {
            Kriteria::create($k);
        }

        $this->command->info('✓ Kriteria MHQ berhasil ditambahkan!');
    }
}
