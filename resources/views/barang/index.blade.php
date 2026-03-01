@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Data Barang</h3>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TOMBOL TAMBAH (HANYA ADMIN) --}}
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">
        + Tambah Barang
    </a>
    @endif

    {{-- FORM CETAK LABEL --}}
    <form action="{{ route('barang.cetak') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Start X</label>
                <input type="number" name="start_x" class="form-control" min="1" max="5" required>
            </div>

            <div class="col-md-3">
                <label>Start Y</label>
                <input type="number" name="start_y" class="form-control" min="1" max="8" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success mb-3">
            Cetak Label
        </button>

        {{-- TABEL --}}
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Pilih</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    @if(auth()->user()->role == 'admin')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($barang as $b)
                <tr>
                    <td>
                        <input type="checkbox" name="barang[]" value="{{ $b->id_barang }}">
                    </td>

                    <td>{{ $b->id_barang }}</td>
                    <td>{{ $b->nama }}</td>
                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>

                    @if(auth()->user()->role == 'admin')
                    <td>
                        {{-- EDIT --}}
                        <a href="{{ route('barang.edit', $b->id_barang) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('barang.destroy', $b->id_barang) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Yakin hapus?')"
                                    class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data kosong
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </form>
</div>
@endsection