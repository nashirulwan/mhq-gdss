<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Peserta;
use Illuminate\Support\Facades\DB;

class UpdatePesertaUserIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with 'peserta' role
        $pesertaUsers = User::where('role', 'peserta')->get();

        foreach ($pesertaUsers as $user) {
            // Find peserta record by name or email match
            $peserta = Peserta::where('nama_lengkap', $user->name)
                ->orWhere('kontak', $user->email)
                ->first();

            if ($peserta) {
                $peserta->update(['user_id' => $user->id]);
                $this->command->info("Updated peserta '{$peserta->nama_lengkap}' with user_id {$user->id}");
            } else {
                // Create a new peserta record if none exists
                $peserta = Peserta::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $user->name,
                    'nomor_peserta' => 'PS' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'kontak' => $user->email,
                    'instansi' => 'Institusi Default',
                    'kategori' => 'Quran Hafalan',
                    'usia' => 20,
                ]);
                $this->command->info("Created new peserta '{$peserta->nama_lengkap}' with user_id {$user->id}");
            }
        }

        // Also update profile records for peserta users
        foreach ($pesertaUsers as $user) {
            $peserta = Peserta::where('user_id', $user->id)->first();
            if ($peserta && $user->profile) {
                $user->profile->update([
                    'nomor_peserta' => $peserta->nomor_peserta,
                    'instansi' => $peserta->instansi,
                    'kategori' => $peserta->kategori,
                    'usia' => $peserta->usia,
                    'bio' => $peserta->keterangan,
                ]);
            }
        }
    }
}