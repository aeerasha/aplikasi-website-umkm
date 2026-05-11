<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
</head>
<body>

    <h1>Daftar Produk</h1>

    <a href="/products/create">
        Tambah Produk
    </a>

    <hr>

    @forelse($products as $product)

        <div style="margin-bottom:20px;">

            <h3>{{ $product->name }}</h3>

            <p>{{ $product->description }}</p>

            <p>Harga: Rp {{ $product->price }}</p>

            <p>Stock: {{ $product->stock }}</p>

        </div>

        <hr>

    @empty

        <p>Belum ada produk.</p>

    @endforelse

</body>
</html>