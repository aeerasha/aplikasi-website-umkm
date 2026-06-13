<?php
class UlasanController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // 1. TAMPILAN ADMIN — LEFT JOIN agar ulasan tamu (user_id=NULL) ikut tampil
    public function adminIndex(): void {
        requireRole('admin');

        $search = trim($_GET['search'] ?? '');
        $params = [];

        $sql = "
            SELECT
                ul.*,
                COALESCE(u.nama, ul.nama_pelanggan, 'Tamu') AS nama_user,
                pr.nama AS nama_produk
            FROM ulasan ul
            LEFT JOIN users  u  ON u.id  = ul.user_id
            LEFT JOIN produk pr ON pr.id = ul.produk_id
            WHERE 1=1
        ";

        if ($search) {
            $sql   .= " AND (ul.nama_pelanggan LIKE ? OR pr.nama LIKE ? OR ul.komentar LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $sql .= " ORDER BY ul.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $ulasan = $stmt->fetchAll(PDO::FETCH_OBJ);

        renderView('admin/ulasan/index', compact('ulasan', 'search'));
    }

    // 2. TAMPILAN CUSTOMER — LEFT JOIN, ulasan tamu tetap muncul
    public function index(): void {
        $stmt = $this->db->prepare("
            SELECT
                ul.*,
                COALESCE(u.nama, ul.nama_pelanggan, 'Tamu') AS nama_user,
                pr.nama AS nama_produk
            FROM ulasan ul
            LEFT JOIN users  u  ON u.id  = ul.user_id
            LEFT JOIN produk pr ON pr.id = ul.produk_id
            ORDER BY ul.id DESC
        ");
        $stmt->execute();
        $ulasan = $stmt->fetchAll(PDO::FETCH_OBJ);

        renderCustomer('customer/ulasan/index', compact('ulasan'));
    }

    // 8. HAPUS ULASAN DARI SISI ADMIN (tambahan baru)
    public function adminDestroy(): void {
        requireRole('admin');

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            flash('error', 'ID ulasan tidak valid.');
            redirect('/admin/ulasan');
        }

        $this->db->prepare("DELETE FROM ulasan WHERE id = ?")->execute([$id]);
        flash('success', 'Ulasan berhasil dihapus.');
        redirect('/admin/ulasan');
    }

    // 3. FORM TAMBAH
    public function create() {
        $produkList = $this->db->query("SELECT id, nama as nama_produk FROM produk")->fetchAll(PDO::FETCH_OBJ);
        renderCustomer('customer/ulasan/create', compact('produkList'));
    }

    // 4. PROSES SIMPAN — tanpa user_id login, pakai session tamu
    public function store(): void {
        requireCustomer();

        $customer     = $_SESSION['customer'];
        $produk_id    = (int)($_POST['produk_id'] ?? 0);
        $rating       = min(5, max(1, (int)($_POST['rating'] ?? 5)));
        $komentar     = trim($_POST['ulasan'] ?? $_POST['komentar'] ?? '');
        $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
        $nama_tampil  = $is_anonymous ? 'Anonim' : $customer['nama'];

        if (!$produk_id) {
            flash('error', 'Pilih produk terlebih dahulu.');
            redirect('/customer/ulasan/create');
        }
        if (empty($komentar)) {
            flash('error', 'Komentar tidak boleh kosong.');
            redirect('/customer/ulasan/create');
        }

        // user_id = NULL karena customer tidak punya akun login
        $this->db->prepare(
            "INSERT INTO ulasan (user_id, produk_id, nama_pelanggan, rating, komentar, is_anonymous, created_at)
             VALUES (NULL, ?, ?, ?, ?, ?, ?)"
        )->execute([
            $produk_id,
            $nama_tampil,
            $rating,
            $komentar,
            $is_anonymous,
            date('Y-m-d H:i:s'),
        ]);

        flash('success', 'Ulasan berhasil dikirim! Terima kasih.');
        redirect('/customer/ulasan');
    }

    // 5. FORM EDIT
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT ulasan.*, produk.nama as nama_produk FROM ulasan JOIN produk ON ulasan.produk_id = produk.id WHERE ulasan.id = ?");
        $stmt->execute([$id]); 
        $ulasanData = $stmt->fetchObject();
        
        renderCustomer('customer/ulasan/edit', compact('ulasanData'));
    }

    // 6. PROSES UPDATE (Hanya satu fungsi ini saja yang dipakai)
    public function update() {
        $id = (int)$_POST['id'];
        $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;

        // WAJIB pakai 'komentar', bukan 'ulasan'
        $stmt = $this->db->prepare("UPDATE ulasan SET rating = ?, komentar = ?, is_anonymous = ? WHERE id = ?");
        $stmt->execute([(int)$_POST['rating'], $_POST['ulasan'], $is_anonymous, $id]);

        flash('success', 'Ulasan berhasil diperbarui!');
        redirect('/customer/ulasan');
    }

    // 7. PROSES HAPUS
    public function destroy() {
        $id = (int)$_POST['id'];
        $this->db->prepare("DELETE FROM ulasan WHERE id = ?")->execute([$id]);
        flash('success', 'Ulasan berhasil dihapus!');
        redirect('/customer/ulasan');
    }
}