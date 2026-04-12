@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Menu Saya</h3>
        <a href="{{ route('menu.tag-harga.semua') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            🖨️ Cetak Semua Tag Harga
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('menu.create') }}" class="btn btn-primary mb-3">+ Tambah Menu</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Barcode</th>
                <th width="210px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menu as $key => $m)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        @if($m->path_gambar)
                            <img src="{{ asset('storage/' . $m->path_gambar) }}"
                                 alt="{{ $m->nama_menu }}"
                                 style="width:70px; height:70px; object-fit:cover; border-radius:6px;">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $m->nama_menu }}</td>
                    <td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                    <td>
                        {{-- Barcode tampil langsung dari barcodeBase64 yang dikirim controller --}}
                        <img src="data:image/png;base64,{{ $m->barcodeBase64 }}"
                             alt="Barcode {{ $m->idmenu }}"
                             style="height:40px; display:block; max-width:160px;">
                        <small class="text-muted" style="font-size:10px; letter-spacing:2px;">
                            {{ $m->idmenu }}
                        </small>
                    </td>
                    <td>
                        <a href="{{ route('menu.edit', $m->idmenu) }}" class="btn btn-warning btn-sm">Edit</a>

                        {{-- Cetak tag harga PDF 1 menu --}}
                        <a href="{{ route('menu.tag-harga', $m->idmenu) }}" target="_blank"
                           class="btn btn-outline-dark btn-sm">🖨️ Tag</a>

                        <form action="{{ route('menu.destroy', $m->idmenu) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus menu?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada menu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection