@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Data Customer</h4>
        </div>
        <div class="col-auto">
            <a href="{{ route('customer.create1') }}" class="btn btn-primary btn-sm me-1">
                <i class="bi bi-camera"></i> Tambah (BLOB)
            </a>
            <a href="{{ route('customer.create2') }}" class="btn btn-success btn-sm">
                <i class="bi bi-camera"></i> Tambah (File)
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="50">#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Tipe Foto</th>
                        <th>Tgl Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $i => $cust)
                    <tr>
                        <td>{{ $i + 1 }}</td>

                        {{-- FOTO --}}
                        <td>
                            @if($cust->foto_blob)
                                @php
                                    $blob = $cust->foto_blob;
                                    if (is_resource($blob)) {
                                        $blob = stream_get_contents($blob);
                                    }
                                    $base64 = base64_encode($blob);
                                @endphp

                                <img src="data:image/jpeg;base64,{{ $base64 }}"
                                     width="60" height="60"
                                     class="rounded-circle object-fit-cover border"
                                     alt="foto">

                            @elseif($cust->foto_path)
                                <img src="{{ asset('storage/' . $cust->foto_path) }}"
                                     width="60" height="60"
                                     class="rounded-circle object-fit-cover border"
                                     alt="foto">

                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px">
                                    <i class="bi bi-person text-white fs-4"></i>
                                </div>
                            @endif
                        </td>

                        {{-- DATA --}}
                        <td>{{ $cust->nama }}</td>
                        <td>{{ $cust->email ?? '-' }}</td>
                        <td>{{ $cust->no_hp ?? '-' }}</td>

                        {{-- TIPE FOTO --}}
                        <td>
                            @if($cust->foto_blob)
                                <span class="badge bg-primary">BLOB</span>
                            @elseif($cust->foto_path)
                                <span class="badge bg-success">FILE</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- TANGGAL --}}
                        <td>{{ $cust->created_at->format('d/m/Y H:i') }}</td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada data customer.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection