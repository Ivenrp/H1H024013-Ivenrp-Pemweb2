<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProgramStudiSeeder::class,
            MatakuliahSeeder::class,
        ]);

        Mahasiswa::factory()->count(30)->create();

        $this->call([
            MahasiswaMatakuliahSeeder::class,
        ]);
    }
}
