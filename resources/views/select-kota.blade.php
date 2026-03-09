```blade
@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">

    <!-- SELECT BIASA -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Select</h4>
            </div>

            <div class="card-body">

                <label>Kota</label>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="kotaInput1" class="form-control" placeholder="Masukkan kota">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success w-100" onclick="tambahKota1()">Tambahkan</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Select Kota</label>
                    <select id="selectKota1" class="form-control">
                        <option value="">Pilih Kota</option>
                    </select>
                </div>

                <p>Kota Terpilih: <span id="hasil1"></span></p>

            </div>
        </div>
    </div>


    <!-- SELECT 2 -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Select 2</h4>
            </div>

            <div class="card-body">

                <label>Kota</label>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="kotaInput2" class="form-control" placeholder="Masukkan kota">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success w-100" onclick="tambahKota2()">Tambahkan</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Select Kota</label>
                    <select id="selectKota2" class="form-control">
                        <option value="">Pilih Kota</option>
                    </select>
                </div>

                <p>Kota Terpilih: <span id="hasil2"></span></p>

            </div>
        </div>
    </div>

</div>
</div>
@endsection


@push('scripts')

<script>

function tambahKota1(){

    let input = document.getElementById("kotaInput1");
    let kota = input.value;

    if(kota !== ""){
        let option = document.createElement("option");
        option.text = kota;
        option.value = kota;

        document.getElementById("selectKota1").appendChild(option);

        input.value = "";
    }

}

document.getElementById("selectKota1").addEventListener("change", function(){
    document.getElementById("hasil1").innerText = this.value;
});



function tambahKota2(){

    let input = document.getElementById("kotaInput2");
    let kota = input.value;

    if(kota !== ""){
        let option = document.createElement("option");
        option.text = kota;
        option.value = kota;

        document.getElementById("selectKota2").appendChild(option);

        input.value = "";
    }

}

document.getElementById("selectKota2").addEventListener("change", function(){
    document.getElementById("hasil2").innerText = this.value;
});

</script>

@endpush
