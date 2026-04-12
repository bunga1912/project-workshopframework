@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')

<div class="page-header">
    <h3 class="page-title">Edit Vendor</h3>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Vendor</label>
                <input type="text" name="nama_vendor"
                       class="form-control @error('nama_vendor') is-invalid @enderror"
                       value="{{ old('nama_vendor', $vendor->nama_vendor) }}">
                @error('nama_vendor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama User</label>
                <input type="text" name="nama_user"
                       class="form-control @error('nama_user') is-invalid @enderror"
                       value="{{ old('nama_user', $vendor->user->name ?? '') }}">
                @error('nama_user')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $vendor->user->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Password Baru <small>(kosongkan jika tidak diubah)</small></label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save"></i> Update
            </button>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection