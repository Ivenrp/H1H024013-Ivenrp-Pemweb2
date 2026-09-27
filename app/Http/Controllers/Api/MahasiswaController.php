<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kueri = Mahasiswa::query()->with('programStudi');

        if ($request->filled('cari')) {
            $kataKunci = $request->query('cari');
            $kueri->where(function ($sub) use ($kataKunci) {
                $sub->where('nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('nim', 'like', '%' . $kataKunci . '%');
            });
        }

        if ($request->filled('angkatan')) {
            $kueri->where('angkatan', $request->integer('angkatan'));
        }

        if ($request->filled('program_studi_id')) {
            $kueri->where('program_studi_id', $request->integer('program_studi_id'));
        }

        $urutan = $request->query('urut', 'nama');
        $arah   = $request->query('arah', 'asc');
        $kolomDiizinkan = ['nama', 'nim', 'angkatan', 'ipk'];

        if (in_array($urutan, $kolomDiizinkan, true)) {
            $kueri->orderBy($urutan, $arah === 'desc' ? 'desc' : 'asc');
        }

        $perHalaman = max(1, min($request->integer('per_halaman', 10), 100));
        $daftar = $kueri->paginate($perHalaman);

        // Filter fields dipindah ke MahasiswaResource
        return MahasiswaResource::collection($daftar);
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $mahasiswa = Mahasiswa::create($request->validated());

        return (new MahasiswaResource($mahasiswa->load('programStudi')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('programStudi');
        return new MahasiswaResource($mahasiswa);
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($request->validated());
        $mahasiswa->load('programStudi');

        return new MahasiswaResource($mahasiswa);
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Data mahasiswa berhasil dihapus',
        ]);
    }

    // ====== TUGAS 2: endpoint per program studi ======
    public function byProgramStudi(Request $request, $id)
    {
        \App\Models\ProgramStudi::findOrFail($id);

        $perHalaman = max(1, min($request->integer('per_halaman', 10), 100));

        $daftar = Mahasiswa::with('programStudi')
            ->where('program_studi_id', $id)
            ->orderBy('nama')
            ->paginate($perHalaman);

        return MahasiswaResource::collection($daftar);
    }
}
