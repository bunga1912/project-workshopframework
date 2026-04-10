@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
  <h3 class="page-title">Dashboard</h3>
</div>

<div class="row">

  {{-- TOTAL VENDOR --}}
  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-danger card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          Total Vendor
          <i class="mdi mdi-store mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalVendor }}</h2>
        <h6 class="card-text">Vendor terdaftar</h6>
      </div>
    </div>
  </div>

  {{-- TOTAL MENU --}}
  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-info card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          Total Menu
          <i class="mdi mdi-food mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalMenu }}</h2>
        <h6 class="card-text">Menu tersedia</h6>
      </div>
    </div>
  </div>

  {{-- TOTAL PESANAN --}}
  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-warning card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          Total Pesanan
          <i class="mdi mdi-cart mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalPesanan }}</h2>
        <h6 class="card-text">Pesanan masuk</h6>
      </div>
    </div>
  </div>

  {{-- TOTAL USER --}}
  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-success card-img-holder text-white">
      <div class="card-body">
        <h4 class="font-weight-normal mb-3">
          Total User
          <i class="mdi mdi-account mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalUser }}</h2>
        <h6 class="card-text">Pengguna terdaftar</h6>
      </div>
    </div>
  </div>

</div>

@endsection