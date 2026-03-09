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


<!-- MODAL EDIT -->
<div class="modal fade" id="modalBarang" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Edit Barang</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form id="formEditBarang">

<div class="mb-3">
<label>ID Barang</label>
<input type="text" id="editId" class="form-control" readonly>
</div>

<div class="mb-3">
<label>Nama Barang</label>
<input type="text" id="editNama" class="form-control" required>
</div>

<div class="mb-3">
<label>Harga Barang</label>
<input type="text" id="editHarga" class="form-control" required>
</div>

</form>

</div>

<div class="modal-footer">

<button type="button" class="btn btn-danger" id="btnDelete">
Hapus
</button>

<button type="button" class="btn btn-primary" id="btnUpdate">
Ubah
</button>

</div>

</div>
</div>
</div>

@endsection


@push('styles')

<style>

#tableBarang tbody tr:hover{
cursor: pointer;
}

</style>

@endpush


@push('scripts')

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DATATABLES JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function(){

let table = $('#tableBarang').DataTable();

let idBarang = 1;
let selectedRow;


// CREATE DATA
$('#btnSubmit').click(function(){

let form = document.getElementById("formBarang");

if(!form.checkValidity()){
form.reportValidity();
return;
}

let btn = document.getElementById("btnSubmit");

btn.innerHTML = "Loading...";
btn.disabled = true;

let nama = $('#namaBarang').val();
let harga = $('#hargaBarang').val();

table.row.add([
idBarang,
nama,
harga
]).draw(false);

idBarang++;

$('#namaBarang').val('');
$('#hargaBarang').val('');

btn.innerHTML = "Submit";
btn.disabled = false;

});


// CLICK ROW → OPEN MODAL
$('#tableBarang tbody').on('click','tr',function(){

selectedRow = table.row(this);

let data = selectedRow.data();

$('#editId').val(data[0]);
$('#editNama').val(data[1]);
$('#editHarga').val(data[2]);

let modal = new bootstrap.Modal(document.getElementById('modalBarang'));
modal.show();

});


// UPDATE DATA
$('#btnUpdate').click(function(){

let form = document.getElementById("formEditBarang");

if(!form.checkValidity()){
form.reportValidity();
return;
}

let id = $('#editId').val();
let nama = $('#editNama').val();
let harga = $('#editHarga').val();

selectedRow.data([
id,
nama,
harga
]).draw(false);

let modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
modal.hide();

});


// DELETE DATA
$('#btnDelete').click(function(){

selectedRow.remove().draw(false);

let modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
modal.hide();

});

});

</script>

@endpush