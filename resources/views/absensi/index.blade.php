@extends('layouts.app')

@section('content')

{{-- FLASH --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="mdi mdi-clipboard-check"></i> Rekap Absensi</h5>
        <div class="d-flex align-items-center gap-2">
            {{-- Filter tanggal --}}
            <input type="date" id="filter-tanggal" class="form-control form-control-sm"
                   value="{{ date('Y-m-d') }}" onchange="filterTanggal(this.value)"
                   style="width: 160px;">
            {{-- Link ke scanner --}}
            <a href="{{ route('absensi.scanner') }}" class="btn btn-sm btn-primary" target="_blank">
                <i class="mdi mdi-nfc-tap"></i> Buka Scanner
            </a>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="card-body border-bottom">
        <div class="row text-center">
            <div class="col-4">
                <h4 class="font-weight-bold text-primary mb-0" id="total-hadir">
                    {{ $absensis->where('status', 'hadir')->count() }}
                </h4>
                <small class="text-muted">Hadir</small>
            </div>
            <div class="col-4">
                <h4 class="font-weight-bold text-warning mb-0" id="total-telat">
                    {{ $absensis->where('status', 'telat')->count() }}
                </h4>
                <small class="text-muted">Telat</small>
            </div>
            <div class="col-4">
                <h4 class="font-weight-bold text-secondary mb-0" id="total-semua">
                    {{ $absensis->count() }}
                </h4>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tabel-absensi">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Waktu Absen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $i => $abs)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $abs->mahasiswa->nama ?? '—' }}</td>
                        <td><code>{{ $abs->mahasiswa->nim ?? '—' }}</code></td>
                        <td>{{ $abs->waktu_absen->format('d M Y, H:i:s') }}</td>
                        <td>
                            @if($abs->status === 'hadir')
                                <span class="badge badge-success">Hadir</span>
                            @elseif($abs->status === 'telat')
                                <span class="badge badge-warning">Telat</span>
                            @else
                                <span class="badge badge-secondary">{{ $abs->status }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('absensi.destroy', $abs->id) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus data absensi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="mdi mdi-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="mdi mdi-clipboard-remove" style="font-size:32px;"></i>
                            <p class="mt-2 mb-0">Belum ada data absensi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filter tanggal: reload dengan query param
function filterTanggal(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('tanggal', val);
    window.location.href = url.toString();
}

// Set nilai filter dari URL saat halaman load
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('tanggal')) {
        document.getElementById('filter-tanggal').value = params.get('tanggal');
    }
});
</script>
@endpush