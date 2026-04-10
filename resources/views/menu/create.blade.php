@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Menu</h3>

    {{-- TAMPILKAN ERROR VALIDASI --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/menu') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control"
                value="{{ old('nama_menu') }}" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="text" name="harga" class="form-control"
                value="{{ old('harga') }}" required>
        </div>

        <div class="mb-3">
            <label>Foto Menu</label>
            <input type="file" name="path_gambar" class="form-control">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ url('/menu') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection