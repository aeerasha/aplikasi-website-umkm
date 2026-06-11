<?php
class StokController {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); requireRole('admin'); }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        if ($search) {
            $stmt = $this->db->prepare("SELECT * FROM stok WHERE nama_bahan LIKE ? OR supplier LIKE ? ORDER BY id DESC");
            $stmt->execute(["%$search%", "%$search%"]);
        } else {
            $stmt = $this->db->query("SELECT * FROM stok ORDER BY id DESC");
        }
        $stoks = $stmt->fetchAll();
        renderView('stok/index', compact('stoks', 'search'));
    }

    public function create(): void { renderView('stok/create', []); }

    public function store(): void {
        $data = [
            'nama_bahan'   => trim($_POST['nama_bahan'] ?? ''),
            'satuan'       => trim($_POST['satuan'] ?? ''),
            'jumlah'       => (float)($_POST['jumlah'] ?? 0),
            'stok_minimum' => (float)($_POST['stok_minimum'] ?? 0),
            'harga_satuan' => (float)($_POST['harga_satuan'] ?? 0),
            'supplier'     => trim($_POST['supplier'] ?? ''),
        ];
        if (empty($data['nama_bahan'])) { flash('error', 'Nama bahan wajib diisi.'); redirect('/stok/create'); }
        $stmt = $this->db->prepare("INSERT INTO stok (nama_bahan, satuan, jumlah, stok_minimum, harga_satuan, supplier) VALUES (?,?,?,?,?,?)");
        $stmt->execute(array_values($data));
        flash('success', 'Stok bahan baku berhasil ditambahkan!');
        redirect('/stok');
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM stok WHERE id=?");
        $stmt->execute([$id]);
        $stok = $stmt->fetchObject();
        if (!$stok) { flash('error', 'Data stok tidak ditemukan.'); redirect('/stok'); }
        renderView('stok/edit', compact('stok'));
    }

    public function update(): void {
        $id   = (int)($_POST['id'] ?? 0);
        $data = [
            'nama_bahan'   => trim($_POST['nama_bahan'] ?? ''),
            'satuan'       => trim($_POST['satuan'] ?? ''),
            'jumlah'       => (float)($_POST['jumlah'] ?? 0),
            'stok_minimum' => (float)($_POST['stok_minimum'] ?? 0),
            'harga_satuan' => (float)($_POST['harga_satuan'] ?? 0),
            'supplier'     => trim($_POST['supplier'] ?? ''),
        ];
        if (empty($data['nama_bahan'])) { flash('error', 'Nama bahan wajib diisi.'); redirect("/stok/edit?id=$id"); }
        $stmt = $this->db->prepare("UPDATE stok SET nama_bahan=?, satuan=?, jumlah=?, stok_minimum=?, harga_satuan=?, supplier=? WHERE id=?");
        $stmt->execute([...array_values($data), $id]);
        flash('success', 'Data stok berhasil diperbarui!');
        redirect('/stok');
    }

    public function destroy(): void {
        $id = (int)($_POST['id'] ?? 0);
        $this->db->prepare("DELETE FROM stok WHERE id=?")->execute([$id]);
        flash('success', 'Data stok berhasil dihapus!');
        redirect('/stok');
    }
}
