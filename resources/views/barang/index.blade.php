@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Data Barang</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ADMIN --}}
    @if(auth()->user()->role == 'admin')
        <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">
            + Tambah Barang
        </a>
    @endif

    <form action="{{ route('barang.label') }}" method="POST" id="formLabel">
        @csrf

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Start X (1-5)</label>
                <input type="number" name="start_x" class="form-control" min="1" max="5" required>
            </div>
            <div class="col-md-3">
                <label>Start Y (1-8)</label>
                <input type="number" name="start_y" class="form-control" min="1" max="8" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success mb-3">
            Cetak Label
        </button>

        <table class="table table-bordered">
            <thead>
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
                @foreach($barang as $b)
                <tr>
                    <td>
                        <input type="checkbox" name="barang[]" value="{{ $b->id_barang }}">
                    </td>
                    <td>{{ $b->id_barang }}</td>
                    <td>{{ $b->nama }}</td>
                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>

                    @if(auth()->user()->role == 'admin')
                    <td>
                        <a href="{{ route('barang.edit', $b->id_barang) }}" class="btn btn-warning btn-sm">Edit</a>

                        {{-- ✅ Pakai button biasa, form hapus di luar --}}
                        <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="hapusBarang('{{ $b->id_barang }}')">
                            Hapus
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    {{-- ✅ Form hapus di LUAR form label --}}
    <form id="formHapus" method="POST" style="display:none">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function hapusBarang(id) {
    if (!confirm('Yakin hapus?')) return;
    const form = document.getElementById('formHapus');
    form.action = '/barang/' + id;
    form.submit();
}
</script>

@endsection