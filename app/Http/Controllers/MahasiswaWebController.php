<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaWebController extends Controller
{
    public function index()
    {
        $daftarMahasiswa = Mahasiswa::with('programStudi')
            ->orderBy('nama')
            ->paginate(10);

        return view('mahasiswa.index', ['daftarMahasiswa' => $daftarMahasiswa]);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['programStudi', 'matakuliah'])
            ->findOrFail($id);

        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_studi_id' => ['required', 'exists:program_studis,id'],
            'nim'              => ['required', 'string', 'max:20', 'unique:mahasiswa,nim'],
            'nama'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'unique:mahasiswa,email'],
            'angkatan'         => ['required', 'integer', 'min:2000'],
        ]);

        Mahasiswa::create($data);

        return redirect()->route('mahasiswa.data')
            ->with('sukses', 'Data mahasiswa berhasil disimpan');
    }
}