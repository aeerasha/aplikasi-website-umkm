<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_NAME', 'UMKM App');

date_default_timezone_set('Asia/Jakarta');


session_start();



require_once BASE_PATH . '/app/Database.php';
require_once BASE_PATH . '/app/helpers.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';
require_once BASE_PATH . '/app/Controllers/CustomerController.php';
require_once BASE_PATH . '/app/Controllers/CustomerPembayaranController.php';

require_once BASE_PATH . '/app/Controllers/PegawaiDashboardController.php';
require_once BASE_PATH . '/app/Controllers/ProdukController.php';
require_once BASE_PATH . '/app/Controllers/PegawaiController.php';
require_once BASE_PATH . '/app/Controllers/StokController.php';
require_once BASE_PATH . '/app/Controllers/PesananController.php';
require_once BASE_PATH . '/app/Controllers/PembayaranController.php';
require_once BASE_PATH . '/app/Controllers/UlasanController.php';
require_once BASE_PATH . '/app/Controllers/RekapController.php';
require_once BASE_PATH . '/app/Controllers/InventarisController.php';
require_once BASE_PATH . '/app/Controllers/SupplierController.php';
if (isset($_GET['url']) && $_GET['url'] === 'customer/pesanan-status') {
    $id = (int)($_GET['id'] ?? 0);
    (new CustomerPembayaranController())->showStatus($id);
    exit; 
}
Database::getInstance();

$uri    = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    // ── Auth ─────────────────────────────────────────────────────────
    'GET /login'     => fn() => (new AuthController())->showLogin(),
    'POST /login'    => fn() => (new AuthController())->login(),
    // register publik dinonaktifkan
    'GET /logout'    => fn() => (new AuthController())->logout(),

    // ── Root ─────────────────────────────────────────────────────────
    'GET /' => function() {
        if (!isset($_SESSION['user'])) redirect('/login');
        match ($_SESSION['user']['role']) {
            'admin'   => redirect('/admin/dashboard'),
            'pegawai' => redirect('/pegawai/dashboard'),
            default => redirect('/customer/identitas'),
        };
    },

    // ── ADMIN Dashboard ───────────────────────────────────────────────
    'GET /admin/dashboard' => function() {
        requireRole('admin');
        $db = Database::getInstance();

        // ── Kartu statistik (semua real-time dari DB) ──────────────────────────
        $totalProduk    = $db->query("SELECT COUNT(*) FROM produk")->fetchColumn();
        $totalPegawai   = $db->query("SELECT COUNT(*) FROM pegawai")->fetchColumn();
        $totalPesanan   = $db->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
        $totalPelanggan = $db->query("SELECT COUNT(*) FROM users WHERE role = 'pelanggan'")->fetchColumn();

        // Total pemasukan: hanya pembayaran lunas
        $totalPemasukan = $db->query(
            "SELECT COALESCE(SUM(jumlah), 0) FROM pembayaran WHERE status = 'lunas'"
        )->fetchColumn();

        // Total pesanan selesai dan pendapatan bersih dari pesanan selesai
        $totalSelesai = $db->query(
            "SELECT COUNT(*) FROM pesanan WHERE status = 'selesai'"
        )->fetchColumn();

        $pendapatanSelesai = $db->query(
            "SELECT COALESCE(SUM(ps.total_harga), 0)
             FROM pesanan ps
             WHERE ps.status = 'selesai'"
        )->fetchColumn();

        // Notifikasi pembayaran menunggu konfirmasi
        $menungguKonfirmasi = $db->query(
            "SELECT COUNT(*) FROM pembayaran WHERE status = 'menunggu_konfirmasi'"
        )->fetchColumn();

        // ── Produk terlaris: SUM dari detail_pesanan, fallback ke pesanan.produk_id ──
        $tableExists = $db->query(
            "SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='detail_pesanan'"
        )->fetchColumn();

        $produkTerlaris = [];
        if ($tableExists) {
            $produkTerlaris = $db->query("
                SELECT
                    pr.nama,
                    SUM(dp.jumlah) AS total
                FROM detail_pesanan dp
                JOIN produk pr ON pr.id = dp.produk_id
                GROUP BY dp.produk_id, pr.nama
                ORDER BY total DESC
                LIMIT 5
            ")->fetchAll();
        }

        // Fallback: pakai COUNT baris pesanan via kolom produk_id lama
        if (empty($produkTerlaris)) {
            $produkTerlaris = [];
        }

        // ── Pesanan terbaru: ambil nama produk dari detail_pesanan atau produk_id lama ──
        $pesananTerbaru = $db->query("
            SELECT
                ps.*,
                COALESCE(
                    (
                        SELECT dp.nama_produk
                        FROM detail_pesanan dp
                        WHERE dp.pesanan_id = ps.id
                        ORDER BY dp.id ASC
                        LIMIT 1
                    ),
                    '-'
                ) AS nama_produk
            FROM pesanan ps
            ORDER BY ps.created_at DESC
            LIMIT 8
        ")->fetchAll();

        // ── Stok kritis ────────────────────────────────────────────────────────
        $stokRendah = $db->query(
            "SELECT * FROM stok WHERE jumlah <= stok_minimum ORDER BY jumlah ASC LIMIT 5"
        )->fetchAll();

        renderView('admin/dashboard', compact(
            'totalProduk', 'totalPegawai', 'totalPesanan', 'totalPelanggan',
            'totalPemasukan', 'totalSelesai', 'pendapatanSelesai',
            'produkTerlaris', 'pesananTerbaru', 'stokRendah', 'menungguKonfirmasi'
        ));
    },

    // ── ADMIN Users ───────────────────────────────────────────────────
    'GET /admin/users'         => function() { requireRole('admin'); $db=Database::getInstance(); $users=$db->query("SELECT * FROM users ORDER BY role,nama")->fetchAll(); renderView('admin/users/index',compact('users')); },
    'GET /admin/users/create'  => function() { requireRole('admin'); renderView('admin/users/create',[]); },
    'POST /admin/users'        => function() {
        requireRole('admin'); $db=Database::getInstance();
        $pw=password_hash($_POST['password']??'pass',PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (nama,email,password,role,telepon,alamat) VALUES (?,?,?,?,?,?)")
           ->execute([$_POST['nama'],$_POST['email'],$pw,$_POST['role'],$_POST['telepon']??'',$_POST['alamat']??'']);
        flash('success','Akun berhasil dibuat!'); redirect('/admin/users');
    },
    'GET /admin/users/edit'    => function() {
        requireRole('admin'); $id=(int)($_GET['id']??0); $db=Database::getInstance();
        $user=$db->prepare("SELECT * FROM users WHERE id=?"); $user->execute([$id]); $user=$user->fetchObject();
        renderView('admin/users/edit',compact('user'));
    },
    'POST /admin/users/update' => function() {
        requireRole('admin'); $db=Database::getInstance(); $id=(int)($_POST['id']??0);
        $db->prepare("UPDATE users SET nama=?,email=?,role=?,telepon=?,alamat=?,aktif=? WHERE id=?")
           ->execute([$_POST['nama'],$_POST['email'],$_POST['role'],$_POST['telepon']??'',$_POST['alamat']??'',(int)($_POST['aktif']??1),$id]);
        if (!empty($_POST['password'])) {
            $db->prepare("UPDATE users SET password=? WHERE id=?")->execute([password_hash($_POST['password'],PASSWORD_DEFAULT),$id]);
        }
        flash('success','Akun diperbarui!'); redirect('/admin/users');
    },
    'POST /admin/users/delete' => function() {
        requireRole('admin'); Database::getInstance()->prepare("DELETE FROM users WHERE id=?")->execute([(int)($_POST['id']??0)]);
        flash('success','Akun dihapus!'); redirect('/admin/users');
    },

    // ── ADMIN Konfirmasi Pembayaran ───────────────────────────────────
    'POST /admin/pembayaran/konfirmasi' => function() {
        requireRole('admin');
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'lunas';
        $db     = Database::getInstance();
        $db->prepare("
            UPDATE pembayaran
            SET status=?,
                verified_by=?,
                verified_at=?
            WHERE id=?
        ")->execute([
            $status,
            $_SESSION['user']['id'],
            date('Y-m-d H:i:s'),
            $id
        ]);
        if ($status === 'lunas') {
            // update status pesanan juga
            $pid = $db->prepare("SELECT pesanan_id FROM pembayaran WHERE id=?");
            $pid->execute([$id]);
            $pesananId = $pid->fetchColumn();
            if ($pesananId) {
                $db->prepare("UPDATE pesanan SET status='diproses' WHERE id=? AND status='pending'")->execute([$pesananId]);
            }
        }
        flash('success', 'Status pembayaran berhasil diperbarui!');
        redirect('/pembayaran');
    },

    // ── ADMIN Rekap ───────────────────────────────────────────────────
    'GET /admin/rekap'        => fn() => (new RekapController())->index(),
    'GET /admin/rekap/export' => fn() => (new RekapController())->export(),

    // ── ADMIN CRUD ────────────────────────────────────────────────────
    'GET /produk'         => fn() => (new ProdukController())->index(),
    'GET /produk/create'  => fn() => (new ProdukController())->create(),
    'POST /produk'        => fn() => (new ProdukController())->store(),
    'GET /produk/edit'    => fn() => (new ProdukController())->edit(),
    'POST /produk/update' => fn() => (new ProdukController())->update(),
    'POST /produk/delete' => fn() => (new ProdukController())->destroy(),

    'GET /pegawai'         => fn() => (new PegawaiController())->index(),
    'GET /pegawai/create'  => fn() => (new PegawaiController())->create(),
    'POST /pegawai'        => fn() => (new PegawaiController())->store(),
    'GET /pegawai/edit'    => fn() => (new PegawaiController())->edit(),
    'POST /pegawai/update' => fn() => (new PegawaiController())->update(),
    'POST /pegawai/delete' => fn() => (new PegawaiController())->destroy(),

    'GET /stok'         => fn() => (new StokController())->index(),
    'GET /stok/create'  => fn() => (new StokController())->create(),
    'POST /stok'        => fn() => (new StokController())->store(),
    'GET /stok/edit'    => fn() => (new StokController())->edit(),
    'POST /stok/update' => fn() => (new StokController())->update(),
    'POST /stok/delete' => fn() => (new StokController())->destroy(),

    'GET /pesanan'         => fn() => (new PesananController())->index(),
    'GET /pesanan/create'  => fn() => (new PesananController())->create(),
    'POST /pesanan'        => fn() => (new PesananController())->store(),
    'GET /pesanan/edit'    => fn() => (new PesananController())->edit(),
    'POST /pesanan/update' => fn() => (new PesananController())->update(),
    'POST /pesanan/delete' => fn() => (new PesananController())->destroy(),

    'GET /pembayaran'         => fn() => (new PembayaranController())->index(),
    'GET /pembayaran/create'  => fn() => (new PembayaranController())->create(),
    'POST /pembayaran'        => fn() => (new PembayaranController())->store(),
    'GET /pembayaran/edit'    => fn() => (new PembayaranController())->edit(),
    'POST /pembayaran/update' => fn() => (new PembayaranController())->update(),
    'POST /pembayaran/delete' => fn() => (new PembayaranController())->destroy(),
    'POST /pembayaran/prosesBayarKasir' => fn() => (new PembayaranController())->prosesBayarKasir(),

    'GET /admin/ulasan'         => fn() => (new UlasanController())->adminIndex(),
    'POST /admin/ulasan/delete' => fn() => (new UlasanController())->adminDestroy(),


    // ── ADMIN Inventaris ──────────────────────────────────────────────
    'GET /inventaris'        => fn() => (new InventarisController())->index(),
    'GET /inventaris/create' => fn() => (new InventarisController())->create(),
    'POST /inventaris'       => fn() => (new InventarisController())->store(),
    'GET /inventaris/edit'   => fn() => (new InventarisController())->edit(),
    'POST /inventaris/update'=> fn() => (new InventarisController())->update(),
    'POST /inventaris/delete'=> fn() => (new InventarisController())->destroy(),

    // ── ADMIN Supplier ────────────────────────────────────────────────
    'GET /supplier'          => fn() => (new SupplierController())->index(),
    'GET /supplier/create'   => fn() => (new SupplierController())->create(),
    'POST /supplier'         => fn() => (new SupplierController())->store(),
    'GET /supplier/edit'     => fn() => (new SupplierController())->edit(),
    'POST /supplier/update'  => fn() => (new SupplierController())->update(),
    'POST /supplier/delete'  => fn() => (new SupplierController())->destroy(),

    // ── PEGAWAI ───────────────────────────────────────────────────────
    'GET /pegawai/dashboard'       => fn() => (new PegawaiDashboardController())->dashboard(),
    'GET /pegawai/antrian'         => fn() => (new PegawaiDashboardController())->antrian(),
    'POST /pegawai/update-status'  => fn() => (new PegawaiDashboardController())->updateStatus(),
    'GET /pegawai/stok-view'       => fn() => (new PegawaiDashboardController())->stokView(),
    'GET /pegawai/profile'         => fn() => (new PegawaiDashboardController())->profile(),

    // ── CUSTOMER ──────────────────────────────────────────────────────
    // Identitas tamu — bypass CustomerController agar tidak terkena requireCustomer()
    'GET /customer/identitas'      => function() {
        if (!empty($_SESSION['customer']['nama'])) { redirect('/customer/katalog'); }
        include BASE_PATH . '/resources/views/customer/identitas.php';
    },
    'POST /customer/identitas' => function() {

        $nama       = trim($_POST['nama'] ?? '');
        $no_hp      = trim($_POST['no_hp'] ?? '');
        $nomor_meja = trim($_POST['nomor_meja'] ?? '');

        if (!$nama || !$no_hp || !$nomor_meja) {
            flash('error','Semua data wajib diisi');
            redirect('/customer/identitas');
        }

        $db = Database::getInstance();

        $stmt = $db->prepare(
            "INSERT INTO customer_guest
            (nama, telepon, nomor_meja)
            VALUES (?,?,?)"
        );

        $stmt->execute([
            $nama,
            $no_hp,
            $nomor_meja
        ]);

        $customerId = $db->lastInsertId();

        $_SESSION['customer'] = [
            'id' => $customerId,
            'nama' => $nama,
            'no_hp' => $no_hp,
            'nomor_meja' => $nomor_meja
        ];

        redirect('/customer/katalog');
    },

    'GET /customer/logout' => function() {

        unset($_SESSION['customer']);

        redirect('/customer/identitas');
    },
    // QRIS & konfirmasi bayar — bypass admin requireRole

    'POST /customer/sudah-bayar' => function() {
        $controller = new PembayaranController(false);
        $controller->konfirmasiSudahBayar();
    },
    'GET /customer/dashboard'      => fn() => (new CustomerController())->dashboard(),
    'GET /customer/katalog'        => fn() => (new CustomerController())->katalog(),
    // Keranjang belanja
    'GET /customer/keranjang'      => fn() => (new CustomerController())->keranjang(),
    'POST /customer/keranjang/tambah' => fn() => (new CustomerController())->tambahKeranjang(),
    'POST /customer/keranjang/hapus'  => fn() => (new CustomerController())->hapusKeranjang(),
    'POST /customer/keranjang/kosongkan' => fn() => (new CustomerController())->kosongkanKeranjang(),
    'GET /customer/checkout' => fn() => (new CustomerController())->checkout(),

    'GET /customer/pesanan-saya'   => fn() => (new CustomerController())->pesananSaya(),
    'GET /customer/buat-pesanan'   => fn() => (new CustomerController())->buatPesanan(),
    'POST /customer/buat-pesanan'  => fn() => (new CustomerController())->storePesanan(),
    'GET /customer/bayar'          => fn() => (new CustomerController())->bayar(),
    'POST /customer/bayar'         => fn() => (new CustomerController())->storeBayar(),
    'GET /customer/riwayat-bayar'  => fn() => (new CustomerController())->riwayatBayar(),
    // ── ROUTE CUSTOMER/USER (CRUD Penuh) ──
    'GET /customer/ulasan'         => fn() => (new UlasanController())->index(),
    'GET /customer/ulasan/create'  => fn() => (new UlasanController())->create(),
    'POST /customer/ulasan'        => fn() => (new UlasanController())->store(),
    'GET /customer/ulasan/edit'    => fn() => (new UlasanController())->edit(),
    'POST /customer/ulasan/update' => fn() => (new UlasanController())->update(),
    'POST /customer/ulasan/delete' => fn() => (new UlasanController())->destroy(),
    'GET /customer/profile'        => fn() => (new CustomerController())->profile(),
    'POST /customer/profile'       => fn() => (new CustomerController())->updateProfile(),
  // Perbaikan rute: gunakan fungsi anonim (fn) agar controller di-instansiasi dengan 'new'
'GET /customer/qris' => fn() => (new CustomerPembayaranController())->tampilkanBayar(),
'POST /customer/final-store' => fn() => (new CustomerPembayaranController())->finalStore(),
'GET /customer/pesanan-status' => function() {
    // Gunakan $_GET['id'] untuk menangkap ID dari URL ?id=5
    $id = $_GET['id'] ?? null; 
    
    if ($id) {
        (new CustomerPembayaranController())->showStatus((int)$id);
    } else {
        // Jika tidak ada ID, coba ambil dari session agar tidak error
        if (isset($_SESSION['last_pesanan_id'])) {
             (new CustomerPembayaranController())->showStatus((int)$_SESSION['last_pesanan_id']);
        } else {
             echo "ID pesanan tidak ditemukan.";
        }
    }
},
  'GET /uploads/bukti' => function() {
        $file = BASE_PATH . '/public/uploads/bukti/' . basename($_GET['file'] ?? '');
        if (file_exists($file)) { readfile($file); } else { http_response_code(404); echo "File not found"; }
    },
];

$key = "$method $uri";
if (isset($routes[$key])) {
    $routes[$key]();
} else {
    // Coba serve file uploads statis
    if (str_starts_with($uri, '/uploads/bukti/')) {
        $file = BASE_PATH . '/public' . $uri;
        if (file_exists($file)) {
            $mime = mime_content_type($file) ?: 'application/octet-stream';
            header("Content-Type: $mime");
            readfile($file);
        } else {
            http_response_code(404);
        }
        exit;
    }
    http_response_code(404);
    echo "<h1 style='font-family:sans-serif;text-align:center;margin-top:80px'>404 — Halaman tidak ditemukan</h1>";
}
