@extends('layouts.app')

@section('judul', 'Detail Mata Kuliah')

@section('konten')
<h1 class="h3 mb-4">Detail Mata Kuliah</h1>

<div class="card shadow-sm" style="max-width: 500px;">
    <div class="card-body">
        <h5 class="card-title">{{ $matakuliah['nama'] }}</h5>
        <h6 class="card-subtitle mb-3 text-muted">Kode: {{ $matakuliah['kode'] }}</h6>
        <p class="card-text">
            Beban Perkuliahan: <x-badge-sks :sks="$matakuliah['sks']" />
        </p>
    </div>
</div>

<a href="{{ route('matakuliah.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar</a>
@endsection