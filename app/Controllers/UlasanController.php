<?php
class UlasanController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // 1. TAMPILAN ADMIN
    public function adminIndex() {
        requireRole('admin');
        $ulasan = $this->db->query("SELECT ulasan.*, users.nama as nama_user, produk.nama as nama_produk 
                                    FROM ulasan 
                                    JOIN users ON ulasan.user_id = users.id 
                                    JOIN produk ON ulasan.produk_id = produk.id 
                                    ORDER BY ulasan.id DESC")->fetchAll(PDO::FETCH_OBJ);
        
        renderView('admin/ulasan/index', compact('ulasan'));
    }

    // 2. TAMPILAN CUSTOMER
    public function index() {
        $ulasan = $this->db->query("SELECT ulasan.*, users.nama as nama_user, produk.nama as nama_produk 
                                    FROM ulasan 
                                    JOIN users ON ulasan.user_id = users.id 
                                    JOIN produk ON ulasan.produk_id = produk.id 
                                    ORDER BY ulasan.id DESC")->fetchAll(PDO::FETCH_OBJ);
        
        renderCustomer('customer/ulasan/index', compact('ulasan')); 
    }

    // 3. FORM TAMBAH
    public function create() {
        $produkList = $this->db->query("SELECT id, nama as nama_produk FROM produk")->fetchAll(PDO::FETCH_OBJ);
        renderCustomer('customer/ulasan/create', compact('produkList'));
    }

    // 4. PROSES SIMPAN
    public function store() {
        $user = auth(); // Mengambil data user yang sedang login
        $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;

        // Pastikan kolom 'nama_pelanggan' ikut dimasukkan dalam INSERT
        $stmt = $this->db->prepare("INSERT INTO ulasan (user_id, produk_id, rating, komentar, is_anonymous, nama_pelanggan) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Kirim $user['nama'] ke database
        $stmt->execute([
            $user['id'], 
            $_POST['produk_id'], 
            (int)$_POST['rating'], 
            $_POST['ulasan'], 
            $is_anonymous,
            $user['nama'] // Menambahkan nama pelanggan dari sesi login
        ]);

        flash('success', 'Ulasan berhasil dikirim!');
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