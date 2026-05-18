@extends('adminlte::page')

@section('title', 'Tambah Produk')

@section('content_header')
    <h1>Tambah Produk</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        <form action="{{ route('products.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            {{-- Nama Produk --}}
            <div class="form-group">
                <label>Nama Produk</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       required>

                @error('name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>


            {{-- Kategori --}}
            <div class="mb-3">
                <label>Jenis Produk</label>

                <select name="category_id" class="form-control" required>

                    <option value="">-- Pilih Jenis --</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>


            {{-- Deskripsi --}}
            <div class="form-group">
                <label>Deskripsi</label>

                <textarea name="description"
                          class="form-control"
                          rows="4"></textarea>
            </div>


            {{-- Harga --}}
            <div class="form-group">
                <label>Harga</label>

                <input type="number"
                       name="price"
                       class="form-control"
                       value="{{ old('price') }}"
                       required>
            </div>


            {{-- Stock --}}
            <div class="form-group">
                <label>Stock</label>

                <input type="number"
                       name="stock"
                       class="form-control"
                       value="{{ old('stock') }}"
                       required>
            </div>


            {{-- Gambar --}}
            <div class="form-group">
                <label>Gambar Produk</label>

                <input type="file"
                       name="image"
                       class="form-control">
            </div>


            <button type="submit"
                    class="btn btn-primary">

                Simpan

            </button>

            <a href="{{ route('products.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </form>

    </div>

</div>

@stop