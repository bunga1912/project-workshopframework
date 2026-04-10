@extends('layouts.app')

@section('title', 'Tambah Vendor')

@section('content')

<div class="page-header">
    <h3 class="page-title">Tambah Vendor</h3>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('vendor.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Vendor</label>
                <input type="text" name="nama_vendor"
                       class="form-control @error('nama_vendor') is-invalid @enderror"
                       value="{{ old('nama_vendor') }}"
                       placeholder="Contoh: Warung Bu Siti">
                @error('nama_vendor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Pemilik</label>
                <input type="text" name="nama_user"
                       class="form-control @error('nama_user') is-invalid @enderror"
                       value="{{ old('nama_user') }}"
                       placeholder="Contoh: Bu Siti">
                @error('nama_user')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Email Login</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="Contoh: siti@gmail.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save"></i> Simpan
            </button>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection