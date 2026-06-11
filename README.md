# Aplikasi UMKM — Laravel-Style PHP MVC

Proyek: **Pengembangan Aplikasi untuk UMKM**  
Manager: Shahira

---

## Fitur (6 CRUD)

| No | Modul | Deskripsi |
|----|-------|-----------|
| 1 | **Produk** | Kelola data produk (nama, kategori, harga, stok) |
| 2 | **Pegawai** | Kelola data pegawai (jabatan, gaji, kontak) |
| 3 | **Stok Bahan Baku** | Pantau & kelola stok bahan baku produksi |
| 4 | **Pesanan** | Kelola pesanan pelanggan & update status |
| 5 | **Pembayaran** | Catat & kelola transaksi pembayaran |
| 6 | **Ulasan** | Kelola ulasan & rating pelanggan |

Semua modul dilengkapi: Create, Read (list + search/filter), Update, Delete.

---

## Cara Menjalankan

### Syarat
- PHP 8.x (dengan ekstensi: `pdo_sqlite`, `sqlite3`, `mbstring`)
- Tidak perlu composer, tidak perlu database terpisah

### Jalankan server
```bash
cd umkm-app/public
php -S localhost:8000
```

Buka browser: **http://localhost:8000**

### Database
Database SQLite otomatis dibuat di `database/umkm.db` saat pertama kali dijalankan.  
Data awal (seeder) otomatis diisi jika tabel kosong.

---

## Struktur Proyek

```
umkm-app/
├── app/
│   ├── Database.php           # Koneksi SQLite + migrasi + seeder
│   ├── helpers.php            # Helper functions (redirect, flash, format, dll)
│   └── Controllers/
│       ├── ProdukController.php
│       ├── PegawaiController.php
│       ├── StokController.php
│       ├── PesananController.php
│       ├── PembayaranController.php
│       └── UlasanController.php
├── database/
│   └── umkm.db                # SQLite database (auto-generated)
├── public/
│   └── index.php              # Entry point & router
└── resources/
    └── views/
        ├── layouts/app.php    # Layout utama (sidebar, topbar)
        ├── dashboard.php      # Dashboard dengan statistik
        ├── produk/            # Views CRUD produk
        ├── pegawai/           # Views CRUD pegawai
        ├── stok/              # Views CRUD stok
        ├── pesanan/           # Views CRUD pesanan
        ├── pembayaran/        # Views CRUD pembayaran
        └── ulasan/            # Views CRUD ulasan
```

---

## Teknologi
- **PHP 8.x** (vanilla, tanpa framework)
- **SQLite** (database embedded)
- **Bootstrap 5.3** (UI)
- **Bootstrap Icons** (ikon)
- Arsitektur MVC (mirip Laravel)
