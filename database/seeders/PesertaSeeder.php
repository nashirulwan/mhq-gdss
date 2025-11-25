<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peserta;
use App\Models\Juri;
use App\Models\Kriteria;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo peserta (participants)
        Peserta::create([
            'nama_lengkap' => 'Ahmad Fauzi',
            'nomor_peserta' => 'PST-001',
            'instansi' => 'Pesantren Al-Hikmah Jakarta',
            'kategori' => '5 Juz',
            'usia' => 19,
            'kontak' => '08123456789',
            'keterangan' => 'Peserta dari Jakarta, berpotensi baik'
        ]);

        Peserta::create([
            'nama_lengkap' => 'Siti Nurhaliza',
            'nomor_peserta' => 'PST-002',
            'instansi' => 'Pesantren Darussalam Bandung',
            'kategori' => '10 Juz',
            'usia' => 18,
            'kontak' => '08234567890',
            'keterangan' => 'Peserta dari Bandung, hafal 10 juz'
        ]);

        Peserta::create([
            'nama_lengkap' => 'Muhammad Rizki',
            'nomor_peserta' => 'PST-003',
            'instansi' => 'Pesantren Nurul Iman Surabaya',
            'kategori' => '15 Juz',
            'usia' => 20,
            'kontak' => '08345678901',
            'keterangan' => 'Peserta dari Surabaya, hafal 15 juz'
        ]);

        Peserta::create([
            'nama_lengkap' => 'Aisyah Putri',
            'nomor_peserta' => 'PST-004',
            'instansi' => 'Pesantren Al-Qur\'an Yogyakarta',
            'kategori' => '3 Juz',
            'usia' => 17,
            'kontak' => '08456789012',
            'keterangan' => 'Peserta dari Yogyakarta, hafal 3 juz'
        ]);

        Peserta::create([
            'nama_lengkap' => 'Budi Santoso',
            'nomor_peserta' => 'PST-005',
            'instansi' => 'Pesantren Ummul Quro Medan',
            'kategori' => '20 Juz',
            'usia' => 19,
            'kontak' => '08567890123',
            'keterangan' => 'Peserta dari Medan, hafal 20 juz'
        ]);

        // Create demo juri (judges)
        Juri::create([
            'nama_lengkap' => 'Ustadz Abdul Hakim',
            'email' => 'abdul.hakim@pesantren.id',
            'keahlian' => 'Tajwid',
            'institusi' => 'Pesantren Al-Hikmah Jakarta',
            'kontak' => '081234567890',
            'is_active' => true
        ]);

        Juri::create([
            'nama_lengkap' => 'Ustadzah Fatimah Zahra',
            'email' => 'fatimah.zahra@pesantren.id',
            'keahlian' => 'Tartil',
            'institusi' => 'Pesantren Darussalam Bandung',
            'kontak' => '082345678901',
            'is_active' => true
        ]);

        Juri::create([
            'nama_lengkap' => 'Ustadz Muhammad Ibrahim',
            'email' => 'muhammad.ibrahim@pesantren.id',
            'keahlian' => 'Fashohah',
            'institusi' => 'Pesantren Nurul Iman Surabaya',
            'kontak' => '083456789012',
            'is_active' => true
        ]);

        // Create demo kriteria (criteria)
        Kriteria::create([
            'nama_kriteria' => 'Tajwid',
            'deskripsi' => 'Ketepatan dalam membaca huruf hijaiyah sesuai dengan ilmu tajwid',
            'bobot' => 30.000,
            'bobot_borda' => 4.000,
            'nilai_max' => 100,
            'nilai_min' => 0,
            'atribut' => 'benefit',
            'is_active' => true
        ]);

        Kriteria::create([
            'nama_kriteria' => 'Tartil',
            'deskripsi' => 'Kelancaran dan keindahan dalam membaca Al-Qur\'an',
            'bobot' => 25.000,
            'bobot_borda' => 3.000,
            'nilai_max' => 100,
            'nilai_min' => 0,
            'atribut' => 'benefit',
            'is_active' => true
        ]);

        Kriteria::create([
            'nama_kriteria' => 'Fashohah',
            'deskripsi' => 'Kefasihan dan kejelasan dalam pelafalan',
            'bobot' => 20.000,
            'bobot_borda' => 2.000,
            'nilai_max' => 100,
            'nilai_min' => 0,
            'atribut' => 'benefit',
            'is_active' => true
        ]);

        Kriteria::create([
            'nama_kriteria' => 'Memorization Accuracy',
            'deskripsi' => 'Ketepatan hafalan dan kelancaran dalam menyebutkan ayat',
            'bobot' => 25.000,
            'bobot_borda' => 3.000,
            'nilai_max' => 100,
            'nilai_min' => 0,
            'atribut' => 'benefit',
            'is_active' => true
        ]);
    }
}
