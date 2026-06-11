<?php
class ProdukController {
    private PDO $db;

    public function __construct() { 
        $this->db = Database::getInstance(); 
        requireRole('admin'); 
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $kategori_id = $_GET['kategori_id'] ?? '';
        
        $params = [];
        $where = [];
        $kategoris = $this->db->query("SELECT * FROM kategori")->fetchAll();
        
        $sql = "SELECT p.*, k.nama as nama_kategori 
                FROM produk p 
                LEFT JOIN kategori k ON p.kategori_id = k.id";
        
        if ($search) {
            $where[] = "p.nama LIKE ?";
            $params[] = "%$search%";
        }
        if ($kategori_id) {
            $where[] = "p.kategori_id = ?";
            $params[] = (int)$kategori_id;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $produks = $stmt->fetchAll();
        
        renderView('produk/index', compact('produks', 'kategoris'));
    }

    public function create(): void { 
        $kategoris = $this->db->query("SELECT * FROM kategori")->fetchAll();
        renderView('produk/create', compact('kategoris')); 
    }

    private function handleImageUpload($file, $oldImage = null) {
        if (isset($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
            $target_dir = BASE_PATH . "/public/uploads/produk/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            if ($oldImage && file_exists($target_dir . $oldImage)) unlink($target_dir . $oldImage);
            
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $file['name']);
            move_uploaded_file($file['tmp_name'], $target_dir . $filename);
            return $filename;
        }
        return $oldImage;
    }

    public function store(): void {
        $nama        = trim($_POST['nama'] ?? '');
        $kategori_id = (int)($_POST['kategori_id'] ?? 0);
        $harga       = (int)($_POST['harga'] ?? 0);
        $stok        = (int)($_POST['stok'] ?? 0);
        $deskripsi   = trim($_POST['deskripsi'] ?? '');
        
        $gambar = $this->handleImageUpload($_FILES['gambar'] ?? null);

        if (empty($nama) || $kategori_id <= 0) { 
            flash('error', 'Nama dan Kategori wajib diisi.'); 
            redirect('/produk/create'); 
        }
        $hargaInput = $_POST['harga'] ?? '';

    // Cek apakah input benar-benar kosong (bukan 0)
    if ($hargaInput === '') {
        flash('error', 'Harga wajib diisi!');
        redirect('/produk/create');
        return;
    }

    $harga = (int)$hargaInput;

    // Cek apakah angka negatif
    if ($harga < 0) {
        flash('error', 'Harga tidak boleh negatif!');
        redirect('/produk/create');
        return;
    }

    if ($stok < 0) {
        flash('error', 'Stok tidak boleh negatif.');
        redirect('/produk/create');
        return;
    }
        
        $stmt = $this->db->prepare("INSERT INTO produk (nama, kategori_id, harga, stok, deskripsi, gambar) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nama, $kategori_id, $harga, $stok, $deskripsi, $gambar]);
        
        flash('success', 'Produk berhasil ditambahkan!');
        redirect('/produk');
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM produk WHERE id=?");
        $stmt->execute([$id]);
        $produk = $stmt->fetchObject();
        
        if (!$produk) { 
            flash('error', 'Produk tidak ditemukan.'); 
            redirect('/produk'); 
        }
        
        $kategoris = $this->db->query("SELECT * FROM kategori")->fetchAll();
        renderView('produk/edit', compact('produk', 'kategoris'));
    }

    public function update(): void {
        $id = (int)($_POST['id'] ?? 0);
        $stmtOld = $this->db->prepare("SELECT gambar FROM produk WHERE id = ?");
        $stmtOld->execute([$id]);
        $oldProduk = $stmtOld->fetchObject();
        
        $nama        = trim($_POST['nama'] ?? '');
        $kategori_id = (int)($_POST['kategori_id'] ?? 0);
        $harga       = (int)($_POST['harga'] ?? 0);
        $stok        = (int)($_POST['stok'] ?? 0);
        $deskripsi   = trim($_POST['deskripsi'] ?? '');

        $gambar = $this->handleImageUpload($_FILES['gambar'] ?? null, $oldProduk->gambar ?? null);

        if (empty($nama)) { 
            flash('error', 'Nama produk wajib diisi.'); 
            redirect("/produk/edit?id=$id"); 
        }
        
        $stmt = $this->db->prepare("UPDATE produk SET nama=?, kategori_id=?, harga=?, stok=?, deskripsi=?, gambar=? WHERE id=?");
        $stmt->execute([$nama, $kategori_id, $harga, $stok, $deskripsi, $gambar, $id]);
        
        flash('success', 'Data produk berhasil diperbarui!');
        redirect('/produk');
    }

    public function destroy(): void {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT gambar FROM produk WHERE id = ?");
        $stmt->execute([$id]);
        $produk = $stmt->fetchObject();
        
        if ($produk && $produk->gambar) {
            $path = BASE_PATH . "/public/uploads/produk/" . $produk->gambar;
            if (file_exists($path)) unlink($path);
        }

        $this->db->prepare("DELETE FROM produk WHERE id=?")->execute([$id]);
        flash('success', 'Produk berhasil dihapus!');
        redirect('/produk');
    }
}