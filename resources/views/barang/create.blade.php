@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="formBarang" action="{{ route('barang.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="text" name="harga" class="form-control" placeholder="Contoh: 10.000" required>
    </div>

    <button type="button" id="btnSubmit" class="btn btn-primary">
        Simpan
    </button>

    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</form>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const hargaInput = document.querySelector('input[name="harga"]');
    const form = document.getElementById('formBarang');
    const btnSubmit = document.getElementById('btnSubmit');

    // FORMAT RUPIAH
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

    btnSubmit.addEventListener('click', function () {

        if (!form.checkValidity()) {

            form.reportValidity();
            return;
        }

        btnSubmit.innerHTML = 'Loading...';

        btnSubmit.disabled = true;

        form.submit();
    });

});

</script>

@endsection