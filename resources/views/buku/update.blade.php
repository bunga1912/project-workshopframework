@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Edit Buku</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Kode Buku</label>
                        <input type="text" name="kode" 
                               class="form-control"
                               value="{{ old('kode', $buku->kode) }}">
                    </div>

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" 
                               class="form-control"
                               value="{{ old('judul', $buku->judul) }}">
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" 
                               class="form-control"
                               value="{{ old('pengarang', $buku->pengarang) }}">
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="idkategori" class="form-control">
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}"
                                    {{ $buku->idkategori == $k->idkategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection