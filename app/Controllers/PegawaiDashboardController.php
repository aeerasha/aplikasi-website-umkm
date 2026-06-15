<?php
class PegawaiDashboardController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        requireRole('pegawai', 'admin');
    }

    // DASHBOARD: Ringkasan untuk Pegawai
    public function dashboard(): void {
        $pesananHariIni = $this->db->query("SELECT COUNT(*) FROM pesanan WHERE DATE(created_at)=DATE('now')")->fetchColumn();
        $pesananPending = $this->db->query("SELECT COUNT(*) FROM pesanan WHERE status='pending'")->fetchColumn();
        $pesananDiproses= $this->db->query("SELECT COUNT(*) FROM pesanan WHERE status='diproses'")->fetchColumn();
        $stokKritis     = $this->db->query("SELECT COUNT(*) FROM stok WHERE jumlah <= stok_minimum")->fetchColumn();

        // Menggunakan JOIN dan GROUP_CONCAT untuk mendapatkan ringkasan produk per pesanan
        $pesananAntrian = $this->db->query(
            "SELECT
                ps.*,
                GROUP_CONCAT(
                    dp.nama_produk || ' x' || dp.jumlah,
                    CHAR(10)
                ) AS daftar_produk
            FROM pesanan ps
            LEFT JOIN detail_pesanan dp
                ON ps.id = dp.pesanan_id
            WHERE ps.status IN ('pending', 'diproses', 'siap diambil')
            GROUP BY ps.id
            ORDER BY ps.created_at ASC
            LIMIT 10"
        )->fetchAll();
        $stokRendah = $this->db->query(
            "SELECT * FROM stok WHERE jumlah <= stok_minimum ORDER BY jumlah ASC LIMIT 5"
        )->fetchAll();

        renderPegawai('pegawai/dashboard', compact(
            'pesananHariIni','pesananPending','pesananDiproses','stokKritis',
            'pesananAntrian','stokRendah'
        ));
    }

    // ANTRIAN: Daftar detail pesanan yang masuk
    public function antrian(): void {
        $status = $_GET['status'] ?? '';
        $sql = "SELECT ps.* FROM pesanan ps WHERE 1=1";
        $params = [];

        if ($status) {
            $sql .= " AND ps.status = ?";
            $params[] = $status;
        } else {
            $sql .= " AND ps.status IN ('pending', 'menunggu_konfirmasi', 'diproses', 'siap diambil')";
        }

        $sql .= " ORDER BY datetime(ps.created_at) DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pesanans = $stmt->fetchAll();

        // Mengambil detail berdasarkan daftar pesanan yang ditemukan
        $detailMap = [];
        if (!empty($pesanans)) {
            $ids = array_map(fn($p) => (int)$p->id, $pesanans);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            $stmtDetail = $this->db->prepare("SELECT * FROM detail_pesanan WHERE pesanan_id IN ($placeholders)");
            $stmtDetail->execute($ids);
            $details = $stmtDetail->fetchAll();
            
            foreach ($details as $d) {
                $detailMap[$d->pesanan_id][] = $d;
            }
        }

        renderPegawai('pegawai/antrian', compact('pesanans', 'detailMap', 'status'));
    }

    // UPDATE STATUS: Mengubah status pesanan
    public function updateStatus(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        $allowed = ['pending', 'diproses', 'siap diambil', 'selesai', 'batal'];

        if (!$id || !in_array($status, $allowed)) {
            flash('error', 'Status tidak valid.');
            redirect('/pegawai/antrian');
        }
        
        $this->db->prepare("UPDATE pesanan SET status = ? WHERE id = ?")->execute([$status, $id]);

        // Jika diproses, pastikan status pembayaran lunas (asumsi QRIS)
        if ($status === 'diproses') {
            $this->db->prepare(
                "UPDATE pembayaran SET status = 'lunas' 
                 WHERE pesanan_id = ? AND status IN ('pending', 'menunggu_konfirmasi')"
            )->execute([$id]);
        }

        flash('success', "Status pesanan diperbarui menjadi '$status'.");
        redirect('/pegawai/antrian');
    }

    public function stokView(): void {
        $stoks = $this->db->query("SELECT * FROM stok ORDER BY nama_bahan")->fetchAll();
        renderPegawai('pegawai/stok', compact('stoks'));
    }

    public function profile(): void {
        $uid  = $_SESSION['user']['id'];
        $user = $this->db->prepare("SELECT u.*, pg.jabatan, pg.gaji FROM users u LEFT JOIN pegawai pg ON pg.user_id=u.id WHERE u.id=?");
        $user->execute([$uid]);
        $user = $user->fetchObject();
        renderPegawai('pegawai/profile', compact('user'));
    }
}