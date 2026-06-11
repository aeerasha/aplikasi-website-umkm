<?php
define('BASE_PATH', dirname(__DIR__));
define('APP_NAME', 'UMKM App');

date_default_timezone_set('Asia/Jakarta');

session_start();

require_once BASE_PATH . '/app/Database.php';
require_once BASE_PATH . '/app/helpers.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';
require_once BASE_PATH . '/app/Controllers/CustomerController.php';
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

Database::getInstance();

$uri    = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    // ── Auth ─────────────────────────────────────────────────────────
    'GET /login'     => fn() => (new AuthController())->showLogin(),
    'POST /login'    => fn() => (new AuthController())->login(),
    'GET /register'  => fn() => (new AuthController())->showRegister(),
    'POST /register' => fn() => (new AuthController())->register(),
    'GET /logout'    => fn() => (new AuthController())->logout(),

    // ── Root ─────────────────────────────────────────────────────────
    'GET /' => function() {
        if (!isset($_SESSION['user'])) redirect('/login');
        match ($_SESSION['user']['role']) {
            'admin'   => redirect('/admin/dashboard'),
            'pegawai' => redirect('/pegawai/dashboard'),
            default   => redirect('/customer/dashboard'),
        };
    },

    // ── ADMIN Dashboard ───────────────────────────────────────────────
    'GET /admin/dashboard' => function() {
        requireRole('admin');
        $db = Database::getInstance();
        $totalProduk    = $db->query("SELECT COUNT(*) FROM produk")->fetchColumn();
        $totalPegawai   = $db->query("SELECT COUNT(*) FROM pegawai")->fetchColumn();
        $totalPesanan   = $db->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
        $totalPelanggan = $db->query("SELECT COUNT(*) FROM users WHERE role='pelanggan'")->fetchColumn();
        $totalPemasukan = $db->query("SELECT COALESCE(SUM(jumlah),0) FROM pembayaran WHERE status='lunas'")->fetchColumn();
        $produkTerlaris = $db->query("SELECT p.nama, COUNT(ps.id) as total FROM pesanan ps JOIN produk p ON p.id=ps.produk_id GROUP BY ps.produk_id ORDER BY total DESC LIMIT 5")->fetchAll();
        $pesananTerbaru = $db->query("SELECT ps.*, p.nama as nama_produk FROM pesanan ps LEFT JOIN produk p ON p.id=ps.produk_id ORDER BY ps.created_at DESC LIMIT 8")->fetchAll();
        $stokRendah     = $db->query("SELECT * FROM stok WHERE jumlah <= stok_minimum ORDER BY jumlah ASC LIMIT 5")->fetchAll();
        $menungguKonfirmasi = $db->query("SELECT COUNT(*) FROM pembayaran WHERE status='menunggu_konfirmasi'")->fetchColumn();
        renderView('admin/dashboard', compact('totalProduk','totalPegawai','totalPesanan','totalPelanggan','totalPemasukan','produkTerlaris','pesananTerbaru','stokRendah','menungguKonfirmasi'));
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
        $db->prepare("UPDATE pembayaran SET status=? WHERE id=?")->execute([$status, $id]);
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

    'GET /admin/ulasan'         => fn() => (new UlasanController())->adminIndex(),


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
    'GET /customer/dashboard'      => fn() => (new CustomerController())->dashboard(),
    'GET /customer/katalog'        => fn() => (new CustomerController())->katalog(),
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

    // ── Static uploads ───────────────────────────────────────────────
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
