<?php
class PegawaiDashboardController {
    private PDO $db;
    public function __construct() {
        $this->db = Database::getInstance();
        requireRole('pegawai', 'admin');
    }

    public function dashboard(): void {
        $pesananHariIni = $this->db->query("SELECT COUNT(*) FROM pesanan WHERE DATE(created_at)=DATE('now')")->fetchColumn();
        $pesananPending = $this->db->query("SELECT COUNT(*) FROM pesanan WHERE status='pending'")->fetchColumn();
        $pesananDiproses= $this->db->query("SELECT COUNT(*) FROM pesanan WHERE status='diproses'")->fetchColumn();
        $stokKritis     = $this->db->query("SELECT COUNT(*) FROM stok WHERE jumlah <= stok_minimum")->fetchColumn();

        $pesananAntrian = $this->db->query(
            "SELECT ps.*, p.nama as nama_produk FROM pesanan ps LEFT JOIN produk p ON p.id=ps.produk_id
             WHERE ps.status IN ('pending','diproses','dikirim') ORDER BY ps.created_at ASC LIMIT 10"
        )->fetchAll();

        $stokRendah = $this->db->query(
            "SELECT * FROM stok WHERE jumlah <= stok_minimum ORDER BY jumlah ASC LIMIT 5"
        )->fetchAll();

        renderPegawai('pegawai/dashboard', compact(
            'pesananHariIni','pesananPending','pesananDiproses','stokKritis',
            'pesananAntrian','stokRendah'
        ));
    }

    public function antrian(): void {
        $status = $_GET['status'] ?? '';
        $params = [];

        $sql = "
            SELECT ps.*,
                   pb.status  AS status_bayar,
                   pb.metode  AS metode_bayar
            FROM pesanan ps
            LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
            WHERE 1=1
        ";

        if ($status) {
            $sql    .= " AND ps.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ps.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pesanans = $stmt->fetchAll();

        // Ambil semua detail item dalam satu query (hindari N+1)
        $detailMap = [];
        if (!empty($pesanans)) {
            $ids = implode(',', array_map(fn($p) => (int)$p->id, $pesanans));

            // Cek apakah tabel detail_pesanan sudah ada
            $tableExists = $this->db->query(
                "SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='detail_pesanan'"
            )->fetchColumn();

            if ($tableExists) {
                $details = $this->db->query("
                    SELECT dp.*, pr.gambar
                    FROM detail_pesanan dp
                    LEFT JOIN produk pr ON pr.id = dp.produk_id
                    WHERE dp.pesanan_id IN ($ids)
                ")->fetchAll();

                foreach ($details as $d) {
                    $detailMap[$d->pesanan_id][] = $d;
                }
            }

            // Fallback: untuk pesanan lama yang belum pakai detail_pesanan
            foreach ($pesanans as $p) {
                if (empty($detailMap[$p->id]) && $p->produk_id) {
                    $fallback = $this->db->prepare(
                        "SELECT pr.nama AS nama_produk, ps.jumlah, ps.total_harga AS subtotal
                         FROM pesanan ps JOIN produk pr ON pr.id = ps.produk_id
                         WHERE ps.id = ?"
                    );
                    $fallback->execute([$p->id]);
                    $row = $fallback->fetchObject();
                    if ($row) $detailMap[$p->id][] = $row;
                }
            }
        }

        renderPegawai('pegawai/antrian', compact('pesanans', 'detailMap', 'status'));
    }

    public function updateStatus(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        $allowed = ['pending', 'menunggu_konfirmasi', 'diproses', 'selesai', 'batal'];

        if (!$id || !in_array($status, $allowed)) {
            flash('error', 'Status tidak valid.');
            redirect('/pegawai/antrian');
        }

        // Cek status saat ini — pesanan selesai/batal tidak bisa diubah lagi
        $stmt = $this->db->prepare("SELECT status FROM pesanan WHERE id = ?");
        $stmt->execute([$id]);
        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/pegawai/antrian');
        }

        if (in_array($pesanan->status, ['selesai', 'batal'])) {
            flash('error', "Pesanan berstatus '{$pesanan->status}' tidak dapat diubah lagi.");
            redirect('/pegawai/antrian');
        }

        $this->db->prepare("UPDATE pesanan SET status = ? WHERE id = ?")->execute([$status, $id]);

        // Jika diproses (kasir approve) → tandai pembayaran lunas
        if ($status === 'diproses') {
            $this->db->prepare(
                "UPDATE pembayaran SET status = 'lunas'
                 WHERE pesanan_id = ? AND status = 'menunggu_konfirmasi'"
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
