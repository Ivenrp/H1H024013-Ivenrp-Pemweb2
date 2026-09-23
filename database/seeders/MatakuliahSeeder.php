<?php

namespace Database\Seeders;

use App\Models\Matakuliah;
use Illuminate\Database\Seeder;

class MatakuliahSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            ['kode' => 'TK0101', 'nama' => 'Pemrograman Dasar',      'sks' => 3, 'semester' => 1],
            ['kode' => 'TK0102', 'nama' => 'Rangkaian Digital',      'sks' => 2, 'semester' => 1],
            ['kode' => 'TK0201', 'nama' => 'Struktur Data dan Algoritma', 'sks' => 3, 'semester' => 2],
            ['kode' => 'TK0202', 'nama' => 'Sistem Operasi',         'sks' => 2, 'semester' => 2],
            ['kode' => 'TK0301', 'nama' => 'Pemrograman Web II',     'sks' => 4, 'semester' => 3],
        ];

        foreach ($daftar as $item) {
            Matakuliah::create($item);
        }
    }
}
