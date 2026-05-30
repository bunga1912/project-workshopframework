@extends('layouts.app')

@section('content')

{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">

    {{-- FORM TAMBAH MAHASISWA --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="mdi mdi-account-plus"></i> Tambah Mahasiswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('mahasiswa.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}" placeholder="Nama mahasiswa" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror"
                               value="{{ old('nim') }}" placeholder="Contoh: 2021001001" required>
                        @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Serial NFC <small class="text-muted">(opsional, bisa diisi nanti)</small></label>
                        <input type="text" name="nfc_serial" class="form-control @error('nfc_serial') is-invalid @enderror"
                               value="{{ old('nfc_serial') }}" placeholder="Contoh: 04:AB:CD:EF:12:34:56">
                        @error('nfc_serial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- TABEL DAFTAR MAHASISWA --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="mdi mdi-account-group"></i> Daftar Mahasiswa</h5>
                <span class="badge badge-primary">{{ $mahasiswas->count() }} mahasiswa</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tabel-mahasiswa">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Serial NFC</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswas as $i => $mhs)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td><code>{{ $mhs->nim }}</code></td>
                                <td>
                                    @if($mhs->nfc_serial)
                                        <span class="badge badge-success">
                                            <i class="mdi mdi-nfc"></i> {{ $mhs->nfc_serial }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Belum terdaftar</span>
                                        <button class="btn btn-xs btn-outline-primary ml-1"
                                                onclick="openRegisterNfc({{ $mhs->id }}, '{{ $mhs->nama }}')">
                                            <i class="mdi mdi-nfc-tap"></i> Daftarkan
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('mahasiswa.edit', $mhs->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Hapus mahasiswa {{ $mhs->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="mdi mdi-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada mahasiswa terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL REGISTER NFC --}}
<div class="modal fade" id="modalRegisterNfc" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-nfc-tap"></i> Daftarkan Kartu NFC
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center py-4">
                <p>Mendaftarkan kartu untuk: <strong id="modal-nama-mhs"></strong></p>

                <div id="modal-status" class="alert alert-info">
                    <i class="mdi mdi-nfc-search-variant"></i>
                    Klik tombol lalu dekatkan kartu NFC...
                </div>

                <button id="btn-modal-scan" class="btn btn-primary" onclick="startRegisterScan()">
                    <i class="mdi mdi-nfc-tap"></i> Scan Kartu
                </button>

                <div id="modal-hasil" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let targetMhsId = null;

function openRegisterNfc(id, nama) {
    targetMhsId = id;
    document.getElementById('modal-nama-mhs').textContent = nama;
    document.getElementById('modal-status').className = 'alert alert-info';
    document.getElementById('modal-status').innerHTML = '<i class="mdi mdi-nfc-search-variant"></i> Klik tombol lalu dekatkan kartu NFC...';
    document.getElementById('modal-hasil').innerHTML = '';
    document.getElementById('btn-modal-scan').disabled = false;
    $('#modalRegisterNfc').modal('show');
}

async function startRegisterScan() {
    if (!('NDEFReader' in window)) {
        document.getElementById('modal-status').className = 'alert alert-danger';
        document.getElementById('modal-status').innerHTML = 'Browser tidak mendukung NFC.';
        return;
    }

    try {
        const ndef = new NDEFReader();
        await ndef.scan();

        document.getElementById('modal-status').className = 'alert alert-info';
        document.getElementById('modal-status').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menunggu kartu...';
        document.getElementById('btn-modal-scan').disabled = true;

        ndef.addEventListener('reading', async ({ serialNumber }) => {
            const res = await fetch(`/mahasiswa/${targetMhsId}/register-nfc`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ serial_number: serialNumber })
            });

            const data = await res.json();

            if (data.status === 'ok') {
                document.getElementById('modal-status').className = 'alert alert-success';
                document.getElementById('modal-status').innerHTML = '<i class="mdi mdi-check-circle"></i> ' + data.message;
                setTimeout(() => { $('#modalRegisterNfc').modal('hide'); location.reload(); }, 1500);
            } else {
                document.getElementById('modal-status').className = 'alert alert-danger';
                document.getElementById('modal-status').innerHTML = '<i class="mdi mdi-alert"></i> ' + (data.message ?? 'Gagal mendaftarkan kartu.');
                document.getElementById('btn-modal-scan').disabled = false;
            }
        });

    } catch (err) {
        document.getElementById('modal-status').className = 'alert alert-danger';
        document.getElementById('modal-status').innerHTML = 'Error: ' + err.message;
        document.getElementById('btn-modal-scan').disabled = false;
    }
}
</script>
@endpush