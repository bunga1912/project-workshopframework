@extends('layouts.app')

@section('content')

<div class="container mt-4">

<h4>Input Barang</h4>

<form id="formBarang">

<div class="mb-3">
<label>Nama</label>
<input type="text" id="namaBarang" class="form-control" required>
</div>

<div class="mb-3">
<label>Harga</label>
<input type="text" id="hargaBarang" class="form-control" required>
</div>

<button type="button" id="btnSubmit" class="btn btn-success">
Submit
</button>

</form>

<hr>

<h4>Data Barang</h4>

<table class="table table-bordered" id="tableBarang">
<thead>
<tr>
<th>ID Barang</th>
<th>Nama</th>
<th>Harga</th>
</tr>
</thead>
<tbody>
</tbody>
</table>

</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

const form = document.getElementById("formBarang");
const btnSubmit = document.getElementById("btnSubmit");
const nama = document.getElementById("namaBarang");
const harga = document.getElementById("hargaBarang");
const table = document.querySelector("#tableBarang tbody");

let idBarang = 1;

btnSubmit.addEventListener("click", function(){

    if(!form.checkValidity()){
        form.reportValidity();
        return;
    }

    btnSubmit.innerHTML = "Loading...";
    btnSubmit.disabled = true;

    let namaValue = nama.value;
    let hargaValue = harga.value;

    let row = `
        <tr>
            <td>${idBarang}</td>
            <td>${namaValue}</td>
            <td>${hargaValue}</td>
        </tr>
    `;

    table.insertAdjacentHTML("beforeend", row);

    idBarang++;

    nama.value = "";
    harga.value = "";


    btnSubmit.innerHTML = "Submit";
    btnSubmit.disabled = false;

});

});

</script>

@endsection