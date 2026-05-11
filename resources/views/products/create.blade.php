<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
</head>
<body>

    <h1>Tambah Produk</h1>

    {{-- Error Validation --}}
    @if ($errors->any())

        <div style="color:red;">

            <ul>
                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach
            </ul>

        </div>

    @endif

    <form action="{{ route('products.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div>
    <label>Kategori</label>
    <br>

    <select name="category_id">

        <option value="">
            -- Pilih Kategori --
        </option>

        @foreach($categories as $category)

            <option value="{{ $category->id }}">

                {{ $category->name }}

            </option>

        @endforeach

    </select>
</div>

<br>

        <div>
            <label>Nama Produk</label>
            <br>

            <input type="text"
                   name="name"
                   value="{{ old('name') }}">
        </div>

        <br>

        <div>
            <label>Deskripsi</label>
            <br>

            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <br>

        <div>
            <label>Harga</label>
            <br>

            <input type="number"
                   name="price"
                   value="{{ old('price') }}">
        </div>

        <br>

        <div>
            <label>Stock</label>
            <br>

            <input type="number"
                   name="stock"
                   value="{{ old('stock') }}">
        </div>

        <br>

        <div>
            <label>Gambar</label>
            <br>

            <input type="file" name="image">
        </div>

        <br>

        <button type="submit">
            Simpan Produk
        </button>

    </form>

</body>
</html>