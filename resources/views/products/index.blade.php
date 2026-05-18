@extends('adminlte::page')

@section('title', 'Data Produk')

@section('content_header')
    <h1>Data Produk</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- ========================= --}}
{{-- FILTER BAR --}}
{{-- ========================= --}}
<div class="mb-3">

    <a href="{{ route('products.index', ['category' => 'all']) }}"
       class="btn btn-sm {{ request('category') == 'all' || !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">
        All
    </a>

    <a href="{{ route('products.index', ['category' => 'Makanan']) }}"
       class="btn btn-sm {{ request('category') == 'Makanan' ? 'btn-primary' : 'btn-outline-primary' }}">
        Makanan
    </a>

    <a href="{{ route('products.index', ['category' => 'Minuman']) }}"
       class="btn btn-sm {{ request('category') == 'Minuman' ? 'btn-primary' : 'btn-outline-primary' }}">
        Minuman
    </a>

    <a href="{{ route('products.index', ['category' => 'Snack']) }}"
       class="btn btn-sm {{ request('category') == 'Snack' ? 'btn-primary' : 'btn-outline-primary' }}">
        Snack
    </a>

</div>

{{-- ========================= --}}
{{-- BUTTON TAMBAH --}}
{{-- ========================= --}}
<div class="mb-3">
    <a href="{{ route('products.create') }}" class="btn btn-success">
        + Tambah Produk
    </a>
</div>

{{-- ========================= --}}
{{-- PRODUCT GRID --}}
{{-- ========================= --}}
<div class="row">

    @forelse($products as $product)

        <div class="col-md-4 mb-3">

            <div class="card shadow-sm">

                {{-- IMAGE --}}
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;">
                @else
                    <img src="https://via.placeholder.com/300x200"
                         class="card-img-top">
                @endif

                <div class="card-body">

                    <h5 class="card-title">
                        {{ $product->name }}
                    </h5>

                    <p class="text-muted mb-1">
                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                    </p>

                    <p class="card-text">
                        <strong>Harga:</strong> Rp {{ number_format($product->price) }} <br>
                        <strong>Stok:</strong> {{ $product->stock }}
                    </p>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('products.edit', $product->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('products.destroy', $product->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus produk?')">
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
                Belum ada produk
            </div>
        </div>

    @endforelse

</div>

@stop