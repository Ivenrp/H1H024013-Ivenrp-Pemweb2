<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Database\Seeder;

class MahasiswaMatakuliahSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa  = Mahasiswa::all();
        $matakuliah = Matakuliah::all();

        if ($mahasiswa->isEmpty() || $matakuliah->isEmpty()) {
            return;
        }

        foreach ($mahasiswa as $mhs) {
            $pilihan = $matakuliah->random(min(3, $matakuliah->count()));
            foreach ($pilihan as $mk) {
                $mhs->matakuliah()->syncWithoutDetaching([
                    $mk->id => ['nilai' => rand(60, 100)],
                ]);
            }
        }
    }
}
