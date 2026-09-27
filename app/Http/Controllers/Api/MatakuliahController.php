<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatakuliahRequest;
use App\Http\Requests\UpdateMatakuliahRequest;
use App\Http\Resources\MatakuliahResource;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $kueri = Matakuliah::query();

        if ($request->filled('cari')) {
            $kataKunci = $request->query('cari');
            $kueri->where(function ($sub) use ($kataKunci) {
                $sub->where('nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('kode', 'like', '%' . $kataKunci . '%');
            });
        }

        if ($request->filled('semester')) {
            $kueri->where('semester', $request->integer('semester'));
        }

        $urutan = $request->query('urut', 'kode');
        $arah   = $request->query('arah', 'asc');
        $kolomDiizinkan = ['kode', 'nama', 'sks', 'semester'];

        if (in_array($urutan, $kolomDiizinkan, true)) {
            $kueri->orderBy($urutan, $arah === 'desc' ? 'desc' : 'asc');
        }

        $perHalaman = max(1, min($request->integer('per_halaman', 10), 100));

        return MatakuliahResource::collection($kueri->paginate($perHalaman));
    }

    public function store(StoreMatakuliahRequest $request)
    {
        $matakuliah = Matakuliah::create($request->validated());
        return (new MatakuliahResource($matakuliah))->response()->setStatusCode(201);
    }

    public function show(Matakuliah $matakuliah)
    {
        return new MatakuliahResource($matakuliah);
    }

    public function update(UpdateMatakuliahRequest $request, Matakuliah $matakuliah)
    {
        $matakuliah->update($request->validated());
        return new MatakuliahResource($matakuliah);
    }

    public function destroy(Matakuliah $matakuliah)
    {
        $matakuliah->delete();
        return response()->json([
            'sukses' => true,
            'pesan'  => 'Data matakuliah berhasil dihapus',
        ]);
    }
}
