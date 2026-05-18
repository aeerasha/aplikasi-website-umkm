@extends('adminlte::page')

@section('title', 'Antrian Pesanan')

@section('content_header')
    <h1>Antrian Pesanan</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Meja</th>
                    <th>Customer</th>
                    <th>Detail Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($orders as $order)

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $order->table_number ?? '-' }}
                    </td>

                    <td>
                        {{ $order->user->name ?? 'Guest' }}
                    </td>

                    <td>
                        @foreach($order->items as $item)
                            <div>
                                • {{ $item->product->name ?? 'Produk' }}
                                ({{ $item->quantity }})
                            </div>
                        @endforeach
                    </td>

                    <td>
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    <td>
                        <span class="badge badge-info">
                            {{ $order->status }}
                        </span>
                    </td>

                    <td>
                        {{ $order->created_at->format('H:i d-m-Y') }}
                    </td>

                    <td>

                        <form method="POST"
                              action="{{ route('employee.orders.updateStatus', $order->id) }}">

                            @csrf
                            @method('PATCH')

                            <select name="status" class="form-control form-control-sm mb-2">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>

                            <button class="btn btn-primary btn-sm btn-block">
                                Update
                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>
</div>

@stop