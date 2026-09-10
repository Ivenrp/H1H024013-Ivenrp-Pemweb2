@extends('layouts.app')

@section('judul', 'Daftar Mata Kuliah')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Daftar Mata Kuliah</h1>
    <form action="{{ route('matakuliah.index') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="q" class="form-control" placeholder="Cari nama atau kode..." value="{{ $kataKunci }}">
        <button type="submit" class="btn btn-primary">Cari</button>
        @if($kataKunci)
            <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
</div>

<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Kode</th>
            <th>Nama Mata Kuliah</th>
            <th>Beban SKS</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($daftarMatakuliah as $mk)
            <tr>
                <td><strong>{{ $mk['kode'] }}</strong></td>
                <td>{{ $mk['nama'] }}</td>
                <td>
                    <x-badge-sks :sks="$mk['sks']" />
                </td>
                <td>
                    <a href="{{ route('matakuliah.show', $mk['kode']) }}" class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-3">Mata kuliah tidak ditemukan</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection