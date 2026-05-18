@extends('adminlte::page')

@section('title', 'Produk Terlaris')

@section('content_header')
    <h1>Produk Terlaris</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Produk Paling Banyak Terjual
        </h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>

            <tbody>

                @forelse($bestProducts as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $item->product->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->total_sold }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="text-center">
                            Belum ada data penjualan
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@stop