<?php
class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dbFolder = BASE_PATH . '/database';
            
            if (!is_dir($dbFolder)) {
                mkdir($dbFolder, 0777, true);
            }

            $dbPath = $dbFolder . '/umkm.db';
            
            // 1. Buat koneksi
            self::$instance = new PDO('sqlite:' . $dbPath);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            
            // 2. PANGGIL FUNGSI MIGRATE DI SINI!
            self::migrate(self::$instance);
        }
        return self::$instance;
    }

    private static function migrate(PDO $db): void {
        $db->exec("PRAGMA journal_mode=WAL;");

        // Tabel-tabel utama
        $db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, nama VARCHAR(100) NOT NULL, email VARCHAR(100) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(20) NOT NULL DEFAULT 'pelanggan', telepon VARCHAR(20), alamat TEXT, aktif INTEGER DEFAULT 1, created_at DATETIME DEFAULT (datetime('now', '+7 hours')))");
        $db->exec("CREATE TABLE IF NOT EXISTS kategori (id INTEGER PRIMARY KEY AUTOINCREMENT, nama VARCHAR(50) NOT NULL)");
        $db->exec("CREATE TABLE IF NOT EXISTS produk (id INTEGER PRIMARY KEY AUTOINCREMENT, nama VARCHAR(100) NOT NULL, kategori_id INTEGER, harga DECIMAL(10,2) NOT NULL, stok INTEGER DEFAULT 0, deskripsi TEXT, gambar VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT (datetime('now', '+7 hours')), FOREIGN KEY (kategori_id) REFERENCES kategori(id))");
        $db->exec("CREATE TABLE IF NOT EXISTS pegawai (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, nama VARCHAR(100) NOT NULL, jabatan VARCHAR(50), email VARCHAR(100), telepon VARCHAR(20), alamat TEXT, gaji DECIMAL(10,2), created_at DATETIME DEFAULT (datetime('now', '+7 hours')), FOREIGN KEY (user_id) REFERENCES users(id))");
        $db->exec("CREATE TABLE IF NOT EXISTS stok (id INTEGER PRIMARY KEY AUTOINCREMENT, nama_bahan VARCHAR(100) NOT NULL, satuan VARCHAR(20), jumlah DECIMAL(10,2) DEFAULT 0, stok_minimum DECIMAL(10,2) DEFAULT 0, harga_satuan DECIMAL(10,2), supplier VARCHAR(100), created_at DATETIME DEFAULT (datetime('now', '+7 hours')))");
        $db->exec("CREATE TABLE IF NOT EXISTS pesanan (id INTEGER PRIMARY KEY AUTOINCREMENT, kode_pesanan VARCHAR(20) UNIQUE, user_id INTEGER, nama_pelanggan VARCHAR(100) NOT NULL, telepon VARCHAR(20), produk_id INTEGER, jumlah INTEGER DEFAULT 1, total_harga DECIMAL(10,2), status VARCHAR(20) DEFAULT 'pending', catatan TEXT, created_at DATETIME DEFAULT (datetime('now', '+7 hours')), FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (produk_id) REFERENCES produk(id))");
        $db->exec("CREATE TABLE IF NOT EXISTS pembayaran (id INTEGER PRIMARY KEY AUTOINCREMENT, pesanan_id INTEGER, metode VARCHAR(30), jumlah DECIMAL(10,2) NOT NULL, status VARCHAR(20) DEFAULT 'pending', catatan TEXT, created_at DATETIME DEFAULT (datetime('now', '+7 hours')), FOREIGN KEY (pesanan_id) REFERENCES pesanan(id))");
        $db->exec("CREATE TABLE IF NOT EXISTS ulasan (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, nama_pelanggan VARCHAR(100) NOT NULL, produk_id INTEGER, rating INTEGER DEFAULT 5, komentar TEXT, is_anonymous TINYINT DEFAULT 0, created_at DATETIME DEFAULT (datetime('now', '+7 hours')), FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (produk_id) REFERENCES produk(id))");
        $db->exec("CREATE TABLE IF NOT EXISTS inventaris (id INTEGER PRIMARY KEY AUTOINCREMENT, nama_alat VARCHAR(100) NOT NULL, kategori VARCHAR(50), jumlah INTEGER DEFAULT 0, kondisi VARCHAR(30) DEFAULT 'Baik', created_at DATETIME DEFAULT (datetime('now', '+7 hours')))");
        $db->exec("CREATE TABLE IF NOT EXISTS supplier (id INTEGER PRIMARY KEY AUTOINCREMENT, nama_supplier VARCHAR(100) NOT NULL, nama_kontak VARCHAR(100), telepon VARCHAR(20), alamat TEXT, keterangan TEXT, created_at DATETIME DEFAULT (datetime('now', '+7 hours')))");

        // Cek apakah database kosong
        $count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($count == 0) {
            self::seed($db);
        }
    }

    private static function seed(PDO $db): void {
    // Users
    $db->exec("INSERT INTO users (nama, email, password, role) VALUES 
        ('Admin', 'admin@umkm.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin'),
        ('Budi Kasir', 'budi@umkm.com', '" . password_hash('pegawai123', PASSWORD_DEFAULT) . "', 'pegawai'),
        ('Andi Pelanggan', 'andi@gmail.com', '" . password_hash('pelanggan123', PASSWORD_DEFAULT) . "', 'pelanggan')");

    // Kategori
    $db->exec("INSERT INTO kategori (nama) VALUES ('Makanan'), ('Minuman'), ('Snack')");

    // Produk
    $db->exec("INSERT INTO produk (nama, kategori_id, harga, stok) VALUES 
        ('Nasi Goreng', 1, 25000, 50),
        ('Mie Ayam', 1, 20000, 40),
        ('Es Teh', 2, 5000, 100),
        ('Keripik', 3, 10000, 30)");

    // Pesanan
    $db->exec("INSERT INTO pesanan (kode_pesanan, user_id, nama_pelanggan, produk_id, total_harga, status) VALUES 
        ('ORD-001', 3, 'Andi Pelanggan', 1, 50000, 'selesai'),
        ('ORD-002', 3, 'Andi Pelanggan', 3, 5000, 'pending')");

    // Pembayaran
    $db->exec("INSERT INTO pembayaran (pesanan_id, metode, jumlah, status) VALUES 
        (1, 'Tunai', 50000, 'lunas'),
        (2, 'Tunai', 5000, 'pending')");

    // --- DATA DUMMY TAMBAHAN ---

    // Inventaris
    $db->exec("INSERT INTO inventaris (nama_alat, kategori, jumlah, kondisi) VALUES 
        ('Blender', 'Dapur', 2, 'Baik'),
        ('Kulkas', 'Pendingin', 1, 'Baik'),
        ('Meja Makan', 'Furniture', 10, 'Baik')");

    // Supplier
    $db->exec("INSERT INTO supplier (nama_supplier, nama_kontak, telepon, alamat, keterangan) VALUES 
        ('PT Maju Jaya', 'Bpk. Ahmad', '08123456789', 'Jl. Industri No. 1', 'Supplier bahan pokok'),
        ('CV Berkah', 'Ibu Siska', '08577788899', 'Jl. Niaga No. 5', 'Supplier kemasan')");
}
}