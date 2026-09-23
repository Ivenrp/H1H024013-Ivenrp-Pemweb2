@extends('layouts.app')

@section('judul', 'Data Mahasiswa')

@section('konten')
<h1 class="h3 mb-4">Data Mahasiswa</h1>

@if (session('sukses'))
<div class="alert alert-success">{{ session('sukses') }}</div>
@endif

<table class="table table-striped bg-white">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Angkatan</th>
            <th>IPK</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($daftarMahasiswa as $mhs)
        <tr>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->nama }}</td>
            <td>{{ $mhs->programStudi->nama ?? '-' }}</td>
            <td>{{ $mhs->angkatan }}</td>
            <td>{{ $mhs->ipk }}</td>
            <td>
                <a href="{{ route('mahasiswa.detail', $mhs->id) }}" class="btn btn-sm btn-primary">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada data mahasiswa.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $daftarMahasiswa->links() }}
@endsection