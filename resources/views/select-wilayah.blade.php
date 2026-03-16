@extends('layouts.app')

@section('content')
<div class="container">

<h3 class="mb-4">Pilih Wilayah Anda</h3>

<div class="row">
<div class="col-md-6">

<div class="card">
<div class="card-body">

<!-- PROVINSI -->   
<div class="mb-3">
<label>Provinsi</label>
<select id="provinsi" class="form-control">
<option value="">Pilih Provinsi</option>

@foreach($provinsi as $p)
<option value="{{ $p->id }}">
{{ $p->name }}
</option>
@endforeach

</select>
</div>


<!-- KOTA -->
<div class="mb-3">
<label>Kota</label>
<select id="kota" class="form-control">
<option value="">Pilih Kota</option>
</select>
</div>


<!-- KECAMATAN -->
<div class="mb-3">
<label>Kecamatan</label>
<select id="kecamatan" class="form-control">
<option value="">Pilih Kecamatan</option>
</select>
</div>


<!-- KELURAHAN -->
<div class="mb-3">
<label>Kelurahan</label>
<select id="kelurahan" class="form-control">
<option value="">Pilih Kelurahan</option>
</select>
</div>

</div>
</div>

</div>
</div>

</div>
@endsection


@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>


// ========================================
// VERSI 1 : AJAX JQUERY
// ========================================

// PROVINSI → KOTA
$('#provinsi').change(function(){

let id = $(this).val()

// reset dropdown
$('#kota').html('<option value="">Pilih Kota</option>')
$('#kecamatan').html('<option value="">Pilih Kecamatan</option>')
$('#kelurahan').html('<option value="">Pilih Kelurahan</option>')

if(id !== ""){

$.ajax({

url:'/get-kota/'+id,
type:'GET',

success:function(data){

data.forEach(function(item){

$('#kota').append(
`<option value="${item.id}">${item.name}</option>`
)

})

}

})

}

})



// KOTA → KECAMATAN
$('#kota').change(function(){

let id = $(this).val()

$('#kecamatan').html('<option value="">Pilih Kecamatan</option>')
$('#kelurahan').html('<option value="">Pilih Kelurahan</option>')

if(id !== ""){

$.ajax({

url:'/get-kecamatan/'+id,
type:'GET',

success:function(data){

data.forEach(function(item){

$('#kecamatan').append(
`<option value="${item.id}">${item.name}</option>`
)

})

}

})

}

})



// KECAMATAN → KELURAHAN
$('#kecamatan').change(function(){

let id = $(this).val()

$('#kelurahan').html('<option value="">Pilih Kelurahan</option>')

if(id !== ""){

$.ajax({

url:'/get-kelurahan/'+id,
type:'GET',

success:function(data){

data.forEach(function(item){

$('#kelurahan').append(
`<option value="${item.id}">${item.name}</option>`
)

})

}

})

}

})



// ========================================
// VERSI 2 : AXIOS
// ========================================

// contoh penggunaan axios

function loadKotaAxios(provinsi_id){

axios.get('/get-kota/'+provinsi_id)

.then(function(response){

let data = response.data

$('#kota').html('<option value="">Pilih Kota</option>')

data.forEach(function(item){

$('#kota').append(
`<option value="${item.id}">${item.name}</option>`
)

})

})

.catch(function(error){

console.log(error)

})

}


</script>

@endpush