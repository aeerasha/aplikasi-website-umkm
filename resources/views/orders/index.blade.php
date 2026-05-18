@extends('adminlte::page')

@section('title', 'Daftar Pesanan')

@section('content_header')
    <h1>Daftar Pesanan (Antrian)</h1>
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
                    <th>Pelanggan</th>
                    <th>Meja</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>

                    <td>
                        {{ $order->user->name ?? 'Guest' }}
                    </td>

                    <td>{{ $order->table_number }}</td>

                    <td>Rp {{ number_format($order->total_price) }}</td>

                    <td>
                        <span class="badge badge-info">
                            {{ $order->status }}
                        </span>
                    </td>

                    <td>
                        {{ $order->created_at->format('d M H:i') }}
                    </td>

                    <td>
                        <form method="POST" action="{{ route('employee.orders.updateStatus', $order->id) }}">                            @csrf
                            @method('PATCH')

                            <select name="status" class="form-control form-control-sm mb-2">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>

                            <button class="btn btn-primary btn-sm">
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