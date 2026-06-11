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
        $sql    = "SELECT ps.*, p.nama as nama_produk FROM pesanan ps LEFT JOIN produk p ON p.id=ps.produk_id WHERE 1=1";
        $params = [];
        if ($status) { $sql .= " AND ps.status=?"; $params[] = $status; }
        $sql .= " ORDER BY ps.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pesanans = $stmt->fetchAll();
        renderPegawai('pegawai/antrian', compact('pesanans','status'));
    }

    public function updateStatus(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $allowed = ['pending','diproses','dikirim','selesai','batal'];
        if (!in_array($status, $allowed)) { flash('error','Status tidak valid.'); redirect('/pegawai/antrian'); }
        $this->db->prepare("UPDATE pesanan SET status=? WHERE id=?")->execute([$status, $id]);
        flash('success','Status pesanan diperbarui!');
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
