@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Barang</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input 
                type="text" 
                name="nama" 
                value="{{ old('nama', $barang->nama) }}" 
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input 
                type="text" 
                name="harga" 
                value="{{ old('harga', 'Rp ' . number_format($barang->harga, 0, ',', '.')) }}" 
                class="form-control"
                required
            >
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    const hargaInput = document.querySelector('input[name="harga"]');

    hargaInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        let number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return rupiah ? 'Rp ' + rupiah : '';
    }
</script>
@endsection