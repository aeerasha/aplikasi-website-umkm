<?php

class PesananController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        requireRole('admin');
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $sql = "
            SELECT
                ps.*,

                (
                    SELECT GROUP_CONCAT(
                        dp.nama_produk || ' x' || dp.jumlah,
                        ', '
                    )
                    FROM detail_pesanan dp
                    WHERE dp.pesanan_id = ps.id
                ) AS nama_produk,

                (
                    SELECT COALESCE(SUM(dp.jumlah), 0)
                    FROM detail_pesanan dp
                    WHERE dp.pesanan_id = ps.id
                ) AS jumlah

            FROM pesanan ps
            WHERE 1=1
        ";

        $params = [];

        if ($search) {
            $sql .= " AND (ps.nama_pelanggan LIKE ? OR ps.kode_pesanan LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($status) {
            $sql .= " AND ps.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ps.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $pesanans = $stmt->fetchAll();

        renderView('pesanan/index', compact(
            'pesanans',
            'search',
            'status'
        ));
    }

    public function create(): void {
        $produks = $this->db
            ->query("SELECT id, nama, harga FROM produk ORDER BY nama")
            ->fetchAll();

        renderView('pesanan/create', compact('produks'));
    }

    public function store(): void {
        flash('error', 'Tambah pesanan manual belum mendukung multi produk.');
        redirect('/pesanan');
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->db->prepare("
            SELECT *
            FROM pesanan
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/pesanan');
        }

        $produks = $this->db
            ->query("SELECT id, nama, harga FROM produk ORDER BY nama")
            ->fetchAll();

        renderView('pesanan/edit', compact(
            'pesanan',
            'produks'
        ));
    }

    public function update(): void {
        flash('error', 'Edit pesanan manual belum mendukung multi produk.');
        redirect('/pesanan');
    }

    public function destroy(): void {
        $id = (int)($_POST['id'] ?? 0);

        $this->db
            ->prepare("DELETE FROM pesanan WHERE id = ?")
            ->execute([$id]);

        flash('success', 'Pesanan berhasil dihapus!');
        redirect('/pesanan');
    }
}