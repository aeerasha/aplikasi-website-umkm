@extends('adminlte::page')

@section('title', 'Rekap Penjualan')

@section('content_header')
    <h1>Rekap Penjualan</h1>
@stop

@section('content')

<div class="row">

    <div class="col-lg-4">

        <div class="small-box bg-info">

            <div class="inner">
                <h3>Rp {{ number_format($dailySales) }}</h3>
                <p>Penjualan Hari Ini</p>
            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="small-box bg-success">

            <div class="inner">
                <h3>Rp {{ number_format($weeklySales) }}</h3>
                <p>Penjualan Mingguan</p>
            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="small-box bg-warning">

            <div class="inner">
                <h3>Rp {{ number_format($monthlySales) }}</h3>
                <p>Penjualan Bulanan</p>
            </div>

        </div>

    </div>

</div>

@stop