<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard UMKM</h1>

<hr>

<h2>Rekap Penjualan</h2>

<p>Harian: Rp {{ $dailySales }}</p>
<p>Mingguan: Rp {{ $weeklySales }}</p>
<p>Bulanan: Rp {{ $monthlySales }}</p>

<hr>

<h2>Produk Terlaris</h2>

<ul>
@forelse($bestProducts as $item)

    <li>
        {{ $item->product->name }}
        -
        {{ $item->total_sold }} terjual
    </li>

@empty

    <li>Belum ada data</li>

@endforelse
</ul>

<hr>

<h2>Produk Kurang Diminati</h2>

<ul>
@forelse($leastProducts as $item)

    <li>
        {{ $item->product->name }}
        -
        {{ $item->total_sold }} terjual
    </li>

@empty

    <li>Belum ada data</li>

@endforelse
</ul>

<hr>

<h2>Produk Tidak Pernah Dibeli</h2>

<ul>
@forelse($unsoldProducts as $product)

    <li>{{ $product->name }}</li>

@empty

    <li>Semua produk pernah dibeli</li>

@endforelse
</ul>

</body>
</html>