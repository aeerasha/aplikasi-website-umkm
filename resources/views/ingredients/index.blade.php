@extends('adminlte::page')

@section('title', 'Stok Bahan Baku')

@section('content_header')
    <h1>Stok Bahan Baku</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- BUTTON TAMBAH --}}
<div class="mb-3">
    <a href="{{ route('ingredients.create') }}" class="btn btn-success">
        + Tambah Bahan
    </a>
</div>

{{-- LIST INGREDIENT --}}
<div class="row">

    @forelse($ingredients as $ingredient)

        <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-body d-flex flex-column">

                    {{-- NAMA --}}
                    <h5 class="font-weight-bold mb-2">
                        {{ $ingredient->name }}
                    </h5>

                    {{-- SATUAN --}}
                    <p class="text-muted mb-3">
                        Satuan: {{ $ingredient->unit }}
                    </p>

                    {{-- STOCK CONTROL --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <strong>Stok</strong>

                        <div class="d-flex align-items-center">

                            {{-- MINUS --}}
                            <form action="{{ route('ingredients.decreaseStock', $ingredient->id) }}"
                                  method="POST"
                                  class="mr-1">

                                @csrf
                                @method('PATCH')

                                <button class="btn btn-danger btn-sm">
                                    -
                                </button>

                            </form>

                            {{-- STOCK --}}
                            <span class="mx-2 font-weight-bold">
                                {{ $ingredient->stock }}
                            </span>

                            {{-- PLUS --}}
                            <form action="{{ route('ingredients.increaseStock', $ingredient->id) }}"
                                  method="POST">

                                @csrf
                                @method('PATCH')

                                <button class="btn btn-success btn-sm">
                                    +
                                </button>

                            </form>

                        </div>

                    </div>

                    {{-- BADGE --}}
                    @if($ingredient->stock == 0)

                        <div class="mb-3">
                            <span class="badge badge-dark">
                                Stok Habis
                            </span>
                        </div>

                    @elseif($ingredient->stock <= 5)

                        <div class="mb-3">
                            <span class="badge badge-danger">
                                Stok Hampir Habis
                            </span>
                        </div>

                    @endif

                    {{-- BUTTON --}}
                    <div class="mt-auto d-flex justify-content-between">

                        <a href="{{ route('ingredients.edit', $ingredient->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('ingredients.destroy', $ingredient->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus bahan?')">
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info text-center">
                Belum ada data bahan baku
            </div>

        </div>

    @endforelse

</div>

@stop