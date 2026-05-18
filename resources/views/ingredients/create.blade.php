@extends('adminlte::page')

@section('title', 'Tambah Bahan Baku')

@section('content_header')
    <h1>Tambah Bahan Baku</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('ingredients.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Bahan</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="unit" class="form-control" placeholder="kg / liter / pcs" required>
            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>
</div>

@stop