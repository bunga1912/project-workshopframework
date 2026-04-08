@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Edit Menu</h3>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4">

        {{-- FORM --}}
        <form action="{{ route('menu.update', $menu->idmenu) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- NAMA MENU --}}
            <div class="mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text" 
                       name="nama_menu" 
                       class="form-control"
                       value="{{ old('nama_menu', $menu->nama_menu) }}" 
                       required>
            </div>

            {{-- HARGA --}}
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="text" 
                       name="harga" 
                       class="form-control"
                       value="{{ old('harga', number_format($menu->harga, 0, ',', '.')) }}" 
                       required>
            </div>

            {{-- FOTO --}}
            <div class="mb-3">
                <label class="form-label">Foto Menu</label>
                <input type="file" 
                       name="path_gambar" 
                       class="form-control">
            </div>

            {{-- PREVIEW FOTO --}}
            @if($menu->path_gambar)
                <div class="mb-3">
                    <label class="form-label">Foto Saat Ini</label><br>
                    <img src="{{ asset('storage/'.$menu->path_gambar) }}" 
                         width="120"
                         style="border-radius:10px; object-fit:cover;">
                </div>
            @endif

            {{-- BUTTON --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>

                <a href="{{ route('menu.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </form>
    </div>

</div>
@endsection