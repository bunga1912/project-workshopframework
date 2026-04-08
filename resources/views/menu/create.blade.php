@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Menu</h3>

    <form action="{{ url('/menu') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>

        <div class="mb-3">
        <   label>Foto Menu</label>
            <input type="file" name="path_gambar" class="form-control">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ url('/menu') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection