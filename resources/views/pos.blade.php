@extends('layouts.app')

@section('content')

<div class="container">

<h3 class="mb-4">Point Of Sales (POS)</h3>

<div class="card p-3 mb-4">

<div class="row g-2 align-items-end">

<div class="col-md-2">
<label>ID Barang</label>
<input type="text" id="id_barang" class="form-control">
</div>

<div class="col-md-3">
<label>Nama Barang</label>
<input type="text" id="nama_barang" class="form-control" readonly>
</div>

<div class="col-md-2">
<label>Harga</label>
<input type="text" id="harga" class="form-control" readonly>
</div>

<div class="col-md-2">
<label>Jumlah</label>
<input type="number" id="jumlah" class="form-control" value="1" min="1">
</div>

<div class="col-md-2 d-grid">
<label>&nbsp;</label>
<button id="btnTambah" class="btn btn-primary" disabled>
Tambahkan
</button>
</div>

</div>

</div>


<table class="table table-bordered" id="tablePOS">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Nama</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Subtotal</th>
<th>Aksi</th>
</tr>
</thead>

<tbody></tbody>

<tfoot>
<tr>
<td colspan="4" align="right"><b>Total</b></td>
<td colspan="2"><b id="total">0</b></td>
</tr>
</tfoot>

</table>


<button id="btnBayar" class="btn btn-success">
Bayar
</button>

</div>

@endsection


@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let total = 0


// ======================
// ENTER CARI BARANG
// ======================

$('#id_barang').keypress(function(e){

if(e.which == 13){

let id = $(this).val()

$.ajax({

url:'/get-barang/'+id,
type:'GET',

success:function(res){

if(res.status){

$('#nama_barang').val(res.data.nama)
$('#harga').val(res.data.harga)
$('#jumlah').val(1)

$('#btnTambah').prop('disabled', false)

}else{

Swal.fire('Error','Barang tidak ditemukan','error')

resetForm()

}

}

})

}

})




// ======================
// VALIDASI JUMLAH
// ======================

$('#jumlah').on('keyup change',function(){

let qty = $(this).val()

if(qty > 0 && $('#nama_barang').val() != ''){
$('#btnTambah').prop('disabled', false)
}else{
$('#btnTambah').prop('disabled', true)
}

})




// ======================
// TAMBAH KE TABLE
// ======================

$('#btnTambah').click(function(){

let id = $('#id_barang').val()
let nama = $('#nama_barang').val()
let harga = parseInt($('#harga').val())
let jumlah = parseInt($('#jumlah').val())

let subtotal = harga * jumlah

let found = false

$('#tablePOS tbody tr').each(function(){

let idTable = $(this).find('.id_barang').text()

if(idTable == id){

let qty = parseInt($(this).find('.qty').val())

qty += jumlah

$(this).find('.qty').val(qty)

let newSubtotal = harga * qty

$(this).find('.subtotal').text(newSubtotal)

found = true

}

})


if(!found){

let row = `
<tr>

<td class="id_barang">${id}</td>
<td>${nama}</td>
<td class="harga">${harga}</td>

<td>
<input type="number" class="form-control qty" value="${jumlah}" min="1">
</td>

<td class="subtotal">${subtotal}</td>

<td>
<button class="btn btn-danger btnHapus">Hapus</button>
</td>

</tr>
`

$('#tablePOS tbody').append(row)

}

hitungTotal()
resetForm()

})




// ======================
// UPDATE JUMLAH
// ======================

$(document).on('change','.qty',function(){

let row = $(this).closest('tr')

let harga = parseInt(row.find('.harga').text())
let qty = parseInt($(this).val())

let subtotal = harga * qty

row.find('.subtotal').text(subtotal)

hitungTotal()

})




// ======================
// HAPUS BARANG
// ======================

$(document).on('click','.btnHapus',function(){

$(this).closest('tr').remove()

hitungTotal()

})




// ======================
// HITUNG TOTAL
// ======================

function hitungTotal(){

total = 0

$('.subtotal').each(function(){

total += parseInt($(this).text())

})

$('#total').text(total)

}




// ======================
// RESET FORM
// ======================

function resetForm(){

$('#id_barang').val('')
$('#nama_barang').val('')
$('#harga').val('')
$('#jumlah').val(1)

$('#btnTambah').prop('disabled', true)

}




// ======================
// BAYAR
// ======================

$('#btnBayar').click(function(){

let btn = this

btn.disabled = true
btn.innerHTML = "Loading..."

let items = []

$('#tablePOS tbody tr').each(function(){

let item = {

kode: $(this).find('.id_barang').text(),
nama: $(this).find('td:eq(1)').text(),
harga: $(this).find('.harga').text(),
jumlah: $(this).find('.qty').val(),
subtotal: $(this).find('.subtotal').text()

}

items.push(item)

})


let data = {

total: $('#total').text(),
items: items

}



$.ajax({

url:'/simpan-transaksi',
type:'POST',
data:data,

headers:{
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},

success:function(res){

Swal.fire(
'Berhasil',
'Transaksi berhasil disimpan',
'success'
).then(()=>{
location.reload()
})

},

error:function(){

Swal.fire(
'Error',
'Gagal menyimpan transaksi',
'error'
)

}

})