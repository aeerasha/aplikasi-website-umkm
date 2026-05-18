@extends('adminlte::page')

@section('title', 'Edit Bahan Baku')

@section('content_header')
    <h1>Edit Stok Bahan Baku</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Bahan</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $ingredient->name ?? '' }}"
                       required>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number"
                       name="stock"
                       class="form-control"
                       value="{{ $ingredient->stock ?? 0 }}"
                       min="0"
                       required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text"
                       name="unit"
                       class="form-control"
                       value="{{ $ingredient->unit ?? '' }}"
                       required>
            </div>

            <button class="btn btn-success">
                Update
            </button>

            <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">
                Batal
            </a>

        </form>

    </div>
</div>

@stop