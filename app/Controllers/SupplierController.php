<?php
class SupplierController {
    private PDO $db;

    public function __construct() {
        requireRole('admin'); // Memastikan hanya admin yang bisa akses
        $this->db = Database::getInstance();
    }

    public function index() {
        $suppliers = $this->db->query("SELECT * FROM supplier ORDER BY id DESC")->fetchAll();
        renderView('admin/supplier/index', compact('suppliers'));
    }

    public function create() {
        renderView('admin/supplier/create');
    }

    public function store() {
    // Validasi: Pastikan 'telepon' hanya berisi angka
    if (!preg_match('/^[0-9]+$/', $_POST['telepon'])) {
        flash('error', 'Nomor telepon harus berupa angka!');
        redirect('/supplier/create'); // Arahkan kembali ke form
        return;
    }

    $this->db->prepare("INSERT INTO supplier (nama_supplier, nama_kontak, telepon, alamat, keterangan) VALUES (?, ?, ?, ?, ?)")
             ->execute([$_POST['nama_supplier'], $_POST['nama_kontak'], $_POST['telepon'], $_POST['alamat'], $_POST['keterangan']]);
    
    flash('success', 'Supplier berhasil ditambahkan!');
    redirect('/supplier');
}

    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM supplier WHERE id = ?");
        $stmt->execute([$id]);
        $supplier = $stmt->fetchObject();
        renderView('admin/supplier/edit', compact('supplier'));
    }

    public function update() {
    // Validasi yang sama untuk update
        if (!preg_match('/^[0-9]+$/', $_POST['telepon'])) {
            flash('error', 'Nomor telepon harus berupa angka!');
            redirect('/supplier/edit?id=' . $_POST['id']);
            return;
        }

        $id = (int)$_POST['id'];
        $this->db->prepare("UPDATE supplier SET nama_supplier = ?, nama_kontak = ?, telepon = ?, alamat = ?, keterangan = ? WHERE id = ?")
                ->execute([$_POST['nama_supplier'], $_POST['nama_kontak'], $_POST['telepon'], $_POST['alamat'], $_POST['keterangan'], $id]);
                
        flash('success', 'Data supplier berhasil diperbarui!');
        redirect('/supplier');
    }

    public function destroy() {
        $id = (int)$_POST['id'];
        $this->db->prepare("DELETE FROM supplier WHERE id = ?")->execute([$id]);
        flash('success', 'Supplier berhasil dihapus!');
        redirect('/supplier');
    }
}