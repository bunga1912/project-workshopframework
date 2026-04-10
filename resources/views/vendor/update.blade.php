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
                <label>Akun User (role: vendor)</label>
                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $vendor->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-content-save"></i> Update
            </button>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection