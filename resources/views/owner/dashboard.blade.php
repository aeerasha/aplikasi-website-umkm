@extends('adminlte::page')

@section('title', 'Dashboard Owner')

@section('content_header')
    <h1>Dashboard Owner</h1>
@stop

@section('content')

{{-- ========================= --}}
{{-- SUMMARY PENJUALAN --}}
{{-- ========================= --}}
<div class="row">

    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Rp {{ number_format($dailySales) }}</h3>
                <p>Penjualan Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp {{ number_format($weeklySales) }}</h3>
                <p>Penjualan Mingguan</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-week"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Rp {{ number_format($monthlySales) }}</h3>
                <p>Penjualan Bulanan</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
    </div>

</div>

{{-- ========================= --}}
{{-- GRAFIK PENJUALAN --}}
{{-- ========================= --}}

@php
    $labels = $dailyChart->pluck('date');
    $values = $dailyChart->pluck('total');
@endphp

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Grafik Penjualan 7 Hari Terakhir</h3>
    </div>

    <div class="card-body">
        <canvas id="salesChart" height="100"></canvas>
    </div>
</div>

{{-- ========================= --}}
{{-- PRODUK TERLARIS --}}
{{-- ========================= --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Produk Terlaris</h3>
    </div>

    <div class="card-body">
        <ul>
            @forelse($bestProducts as $item)
                <li>
                    {{ $item->product->name ?? '-' }} -
                    {{ $item->total_sold }} terjual
                </li>
            @empty
                <li>Tidak ada data</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- ========================= --}}
{{-- PRODUK KURANG LAKU --}}
{{-- ========================= --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Produk Kurang Diminati</h3>
    </div>

    <div class="card-body">
        <ul>
            @forelse($leastProducts as $item)
                <li>
                    {{ $item->product->name ?? '-' }} -
                    {{ $item->total_sold }} terjual
                </li>
            @empty
                <li>Tidak ada data</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- ========================= --}}
{{-- PRODUK BELUM TERJUAL --}}
{{-- ========================= --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Produk Belum Pernah Terjual</h3>
    </div>

    <div class="card-body">
        <ul>
            @forelse($unsoldProducts as $product)
                <li>{{ $product->name }}</li>
            @empty
                <li>Tidak ada data</li>
            @endforelse
        </ul>
    </div>
</div>

@stop

{{-- ========================= --}}
{{-- CHART JS --}}
{{-- ========================= --}}
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($values) !!},
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>
@stop