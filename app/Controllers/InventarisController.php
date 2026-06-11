<?php
class InventarisController {
    private PDO $db;

    public function __construct() {
        requireRole('admin'); // Memastikan hanya admin yang bisa akses
        $this->db = Database::getInstance();
    }

    public function index() {
        $inventaris = $this->db->query("SELECT * FROM inventaris ORDER BY id DESC")->fetchAll();
        renderView('admin/inventaris/index', compact('inventaris'));
    }

    public function create() {
        renderView('admin/inventaris/create');
    }

    public function store() {
        $this->db->prepare("INSERT INTO inventaris (nama_alat, kategori, jumlah, kondisi) VALUES (?, ?, ?, ?)")
             ->execute([$_POST['nama_alat'], $_POST['kategori'], (int)$_POST['jumlah'], $_POST['kondisi']]);
        flash('success', 'Data alat berhasil ditambahkan!');
        redirect('/inventaris');
    }

    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM inventaris WHERE id = ?");
        $stmt->execute([$id]);
        $alat = $stmt->fetchObject();
        renderView('admin/inventaris/edit', compact('alat'));
    }

    public function update() {
        $id = (int)$_POST['id'];
        $this->db->prepare("UPDATE inventaris SET nama_alat = ?, kategori = ?, jumlah = ?, kondisi = ? WHERE id = ?")
             ->execute([$_POST['nama_alat'], $_POST['kategori'], (int)$_POST['jumlah'], $_POST['kondisi'], $id]);
        flash('success', 'Data alat berhasil diperbarui!');
        redirect('/inventaris');
    }

    public function destroy() {
        $id = (int)$_POST['id'];
        $this->db->prepare("DELETE FROM inventaris WHERE id = ?")->execute([$id]);
        flash('success', 'Data alat berhasil dihapus!');
        redirect('/inventaris');
    }
}