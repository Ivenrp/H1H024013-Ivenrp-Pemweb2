@extends('layouts.app')

@section('judul', 'Detail Mahasiswa')

@section('konten')
<h1 class="h3 mb-4">Detail Mahasiswa</h1>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p><strong>Nama:</strong> {{ $mahasiswa->nama }}</p>
        <p><strong>Program Studi:</strong> {{ $mahasiswa->programStudi->nama ?? '-' }}</p>
        <p><strong>Angkatan:</strong> {{ $mahasiswa->angkatan }}</p>
        <p><strong>IPK:</strong> {{ $mahasiswa->ipk }}</p>
        <p><strong>Status:</strong> {{ $mahasiswa->aktif ? 'Aktif' : 'Tidak Aktif' }}</p>
    </div>
</div>

<h2 class="h5">Matakuliah yang Diambil</h2>
<table class="table table-striped bg-white">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Matakuliah</th>
            <th>SKS</th>
            <th>Semester</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mahasiswa->matakuliah as $mk)
        <tr>
            <td>{{ $mk->kode }}</td>
            <td>{{ $mk->nama }}</td>
            <td>{{ $mk->sks }}</td>
            <td>{{ $mk->semester }}</td>
            <td>{{ $mk->pivot->nilai ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Belum mengambil matakuliah.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('mahasiswa.data') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection