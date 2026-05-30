@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="mdi mdi-account-edit"></i> Edit Mahasiswa</h5>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">

                <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $mahasiswa->nama) }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim"
                               class="form-control @error('nim') is-invalid @enderror"
                               value="{{ old('nim', $mahasiswa->nim) }}" required>
                        @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Serial NFC</label>
                        <input type="text" name="nfc_serial"
                               class="form-control @error('nfc_serial') is-invalid @enderror"
                               value="{{ old('nfc_serial', $mahasiswa->nfc_serial) }}"
                               placeholder="Kosongkan jika belum punya kartu">
                        @error('nfc_serial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Format: 04:AB:CD:EF:12:34:56</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary ml-2">Batal</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection