<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator Sistem',
            'email' => 'admin@tahfidz.com',
            'password' => 'password123',
            'role' => User::ROLE_ADMIN,
            'phone' => '+6281234567890',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'Administrator sistem dengan akses penuh ke semua fitur.',
            'keahlian' => 'System Administration',
            'institusi' => 'Tahfidz Competition Management',
        ]);

        // Create Judge Users
        $juriData = [
            [
                'name' => 'Ustadz Ahmad Ridwan',
                'email' => 'ahmad.ridwan@tahfidz.com',
                'keahlian' => 'Tajwid & Fasohah',
                'institusi' => 'Pondok Pesantren Al-Hikmah',
                'bio' => 'Expert dalam ilmu tajwid dan fasohah dengan pengalaman 10 tahun.',
            ],
            [
                'name' => 'Ustadzah Fatimah Az-Zahra',
                'email' => 'fatimah.azzahra@tahfidz.com',
                'keahlian' => 'Kelancaran & Adab',
                'institusi' => 'Islamic Center Jakarta',
                'bio' => 'Spesialis dalam penilaian kelancaran dan adab membaca Al-Quran.',
            ],
            [
                'name' => 'Ustadz Muhammad Ibrahim',
                'email' => 'muhammad.ibrahim@tahfidz.com',
                'keahlian' => 'Tartil & Hafalan',
                'institusi' => 'Ma\'had Al-Jami\'ah',
                'bio' => 'Ahli dalam bidang tartil dan penilaian hafalan Al-Quran.',
            ],
        ];

        foreach ($juriData as $data) {
            $juri = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => 'password123',
                'role' => User::ROLE_JURI,
                'phone' => '+6281234567891',
                'address' => 'Jakarta, Indonesia',
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
                'institusi' => $data['institusi'],
            ]);

            Profile::create([
                'user_id' => $juri->id,
                'keahlian' => $data['keahlian'],
                'institusi' => $data['institusi'],
                'bio' => $data['bio'],
            ]);
        }

        // Create Sample Participants
        $pesertaData = [
            [
                'name' => 'Ali bin Abdullah',
                'email' => 'ali.abdullah@tahfidz.com',
                'nomor_peserta' => 'PST001',
                'instansi' => 'SDIT Al-Furqan',
                'kategori' => 'anak',
                'usia' => 12,
            ],
            [
                'name' => 'Aisyah binti Muhammad',
                'email' => 'aisyah.muhammad@tahfidz.com',
                'nomor_peserta' => 'PST002',
                'instansi' => 'SMP Islam Nurul Hikmah',
                'kategori' => 'remaja',
                'usia' => 15,
            ],
            [
                'name' => 'Abdullah Rahman',
                'email' => 'abdul.rahman@tahfidz.com',
                'nomor_peserta' => 'PST003',
                'instansi' => 'MA Darul Ulum',
                'kategori' => 'remaja',
                'usia' => 17,
            ],
            [
                'name' => 'Khadijah binti Umar',
                'email' => 'khadijah.umar@tahfidz.com',
                'nomor_peserta' => 'PST004',
                'instansi' => 'SMK Islam Al-Azhar',
                'kategori' => 'remaja',
                'usia' => 16,
            ],
            [
                'name' => 'Umar bin Khattab',
                'email' => 'umar.khattab@tahfidz.com',
                'nomor_peserta' => 'PST005',
                'instansi' => 'Pondok Pesantren Darussalam',
                'kategori' => 'dewasa',
                'usia' => 22,
            ],
        ];

        foreach ($pesertaData as $data) {
            $peserta = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => 'password123',
                'role' => User::ROLE_PESERTA,
                'phone' => '+6281234567892',
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
                'institusi' => $data['instansi'],
            ]);

            Profile::create([
                'user_id' => $peserta->id,
                'nomor_peserta' => $data['nomor_peserta'],
                'instansi' => $data['instansi'],
                'kategori' => $data['kategori'],
                'usia' => $data['usia'],
                'bio' => 'Peserta kompetisi tahfidz dengan semangat untuk memperdalam Al-Quran.',
            ]);
        }

        $this->command->info('✅ Users and profiles created successfully!');
        $this->command->info('👤 Login Credentials:');
        $this->command->info('   Admin: admin@tahfidz.com / password123');
        $this->command->info('   Juri: ahmad.ridwan@tahfidz.com / password123');
        $this->command->info('   Peserta: ali.abdullah@tahfidz.com / password123');
    }
}
