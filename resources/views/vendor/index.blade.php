@extends('layouts.app')

@section('title', 'Vendor')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Vendor</h3>
    <a href="{{ route('vendor.create') }}" class="btn btn-primary btn-sm">
        <i class="mdi mdi-plus"></i> Tambah Vendor
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Vendor</th>
                    <th>Akun User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $i => $vendor)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $vendor->nama_vendor }}</td>
                    <td>{{ $vendor->user->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('vendor.edit', $vendor->idvendor) }}"
                           class="btn btn-warning btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('vendor.destroy', $vendor->idvendor) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus vendor ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="mdi mdi-delete"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada vendor</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection