<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MatakuliahController;

class MatakuliahController extends Controller
{
    private array $daftarMatakuliah = [
        ['kode' => 'TKO101', 'nama' => 'Pemrograman Dasar', 'sks' => 3],
        ['kode' => 'TKO102', 'nama' => 'Rangkaian Digital', 'sks' => 2],
        ['kode' => 'TKO201', 'nama' => 'Struktur Data dan Algoritma', 'sks' => 3],
        ['kode' => 'TKO202', 'nama' => 'Sistem Operasi', 'sks' => 2],
        ['kode' => 'TKO301', 'nama' => 'Pemrograman Web II', 'sks' => 4],
    ];

    public function index(Request $request)
    {
        $kataKunci = $request->query('q', '');
        $matakuliah = $this->daftarMatakuliah;

        if ($kataKunci !== '') {
            $matakuliah = array_filter($matakuliah, function ($mk) use ($kataKunci) {
                return stripos($mk['nama'], $kataKunci) !== false ||
                       stripos($mk['kode'], $kataKunci) !== false;
            });
        }

        return view('matakuliah.index', [
            'daftarMatakuliah' => $matakuliah,
            'kataKunci'        => $kataKunci,
        ]);
    }

    public function show(string $kode)
    {
        $matakuliah = collect($this->daftarMatakuliah)->firstWhere('kode', $kode);

        if (!$matakuliah) {
            abort(404, 'Mata kuliah tidak ditemukan');
        }

        return view('matakuliah.show', ['matakuliah' => $matakuliah]);
    }
}