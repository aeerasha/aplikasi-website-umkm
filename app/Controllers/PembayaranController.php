<?php
class PembayaranController {
    protected PDO $db;
    public function __construct(bool $adminOnly = true)
    {
        $this->db = Database::getInstance();

        if ($adminOnly) {
            requireRole('admin');
        }
    }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $sql = "SELECT pb.*, ps.kode_pesanan, ps.nama_pelanggan FROM pembayaran pb LEFT JOIN pesanan ps ON ps.id=pb.pesanan_id WHERE 1=1";
        $params = [];
        if ($search) { $sql .= " AND (ps.nama_pelanggan LIKE ? OR ps.kode_pesanan LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        if ($status) { $sql .= " AND pb.status=?"; $params[] = $status; }
        $sql .= " ORDER BY pb.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pembayarans = $stmt->fetchAll();
        renderView('pembayaran/index', compact('pembayarans', 'search', 'status'));
    }

    public function create(): void {
        $pesanans = $this->db->query("SELECT id, kode_pesanan, nama_pelanggan, total_harga FROM pesanan ORDER BY id DESC")->fetchAll();
        renderView('pembayaran/create', compact('pesanans'));
    }

    public function store(): void {
        $data = [
            'pesanan_id' => (int)($_POST['pesanan_id'] ?? 0) ?: null,
            'metode'     => trim($_POST['metode'] ?? ''),
            'jumlah'     => (float)($_POST['jumlah'] ?? 0),
            'status'     => $_POST['status'] ?? 'pending',
            'catatan'    => trim($_POST['catatan'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if (!$data['jumlah']) { flash('error', 'Jumlah pembayaran wajib diisi.'); redirect('/pembayaran/create'); }
        $stmt = $this->db->prepare("INSERT INTO pembayaran (pesanan_id, metode, jumlah, status, catatan, created_at) VALUES (?,?,?,?,?,?)");
        $stmt->execute(array_values($data));
        flash('success', 'Pembayaran berhasil dicatat!');
        redirect('/pembayaran');
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM pembayaran WHERE id=?");
        $stmt->execute([$id]);
        $pembayaran = $stmt->fetchObject();
        if (!$pembayaran) { flash('error', 'Data pembayaran tidak ditemukan.'); redirect('/pembayaran'); }
        $pesanans = $this->db->query("SELECT id, kode_pesanan, nama_pelanggan, total_harga FROM pesanan ORDER BY id DESC")->fetchAll();
        renderView('pembayaran/edit', compact('pembayaran', 'pesanans'));
    }

    public function update(): void {
        $id   = (int)($_POST['id'] ?? 0);
        $data = [
            'pesanan_id' => (int)($_POST['pesanan_id'] ?? 0) ?: null,
            'metode'     => trim($_POST['metode'] ?? ''),
            'jumlah'     => (float)($_POST['jumlah'] ?? 0),
            'status'     => $_POST['status'] ?? 'pending',
            'catatan'    => trim($_POST['catatan'] ?? ''),
        ];
        $stmt = $this->db->prepare("UPDATE pembayaran SET pesanan_id=?, metode=?, jumlah=?, status=?, catatan=? WHERE id=?");
        $stmt->execute([...array_values($data), $id]);
        flash('success', 'Pembayaran berhasil diperbarui!');
        redirect('/pembayaran');
    }

    public function destroy(): void {
        $id = (int)($_POST['id'] ?? 0);
        $this->db->prepare("DELETE FROM pembayaran WHERE id=?")->execute([$id]);
        flash('success', 'Data pembayaran berhasil dihapus!');
        redirect('/pembayaran');
    }

    public function prosesBayarKasir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $metode = $_POST['metode'];
            
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE pembayaran SET status = 'lunas', metode = ? WHERE id = ?");
            $stmt->execute([$metode, $id]);
            
            // Redirect kembali ke halaman pembayaran dengan pesan sukses jika perlu
            header('Location: /pembayaran');
            exit;
        }
    }

    // ── QRIS Dummy (GET) — halaman pembayaran untuk customer tamu ────────────
    // Route ini TIDAK memakai __construct requireRole sehingga dipanggil
    // langsung dari closure di index.php dengan instance baru tanpa proteksi admin.
    public function showQris(): void {
        $pesananId = (int)($_GET['pesanan_id'] ?? 0);
        if (!$pesananId) {
            flash('error', 'ID pesanan tidak valid.');
            redirect('/customer/katalog');
        }

        $stmt = $this->db->prepare(
            "SELECT ps.*, pb.id as bayar_id, pb.status as status_bayar
             FROM pesanan ps
             LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
             WHERE ps.id = ?"
        );
        $stmt->execute([$pesananId]);
        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/customer/katalog');
        }

        if (in_array($pesanan->status_bayar ?? '', ['menunggu_konfirmasi', 'lunas'])) {
            flash('error', 'Pembayaran untuk pesanan ini sudah dikirim sebelumnya.');
            redirect('/customer/katalog');
        }

        // Render halaman QRIS (standalone, tanpa layout admin)
        $db = $this->db;
        include BASE_PATH . '/resources/views/customer/qris.php';
    }

    // ── Simulasi "Sudah Bayar" (POST) ─────────────────────────────────────────
    public function konfirmasiSudahBayar(): void {
        $pesananId = (int)($_POST['pesanan_id'] ?? 0);
        if (!$pesananId) {
            flash('error', 'ID pesanan tidak valid.');
            redirect('/customer/katalog');
        }

        $stmt = $this->db->prepare("SELECT * FROM pesanan WHERE id = ?");
        $stmt->execute([$pesananId]);
        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/customer/katalog');
        }

        // Cek apakah record pembayaran sudah ada
        $cek = $this->db->prepare("SELECT id FROM pembayaran WHERE pesanan_id = ?");
        $cek->execute([$pesananId]);
        $bayarId = $cek->fetchColumn();

        if ($bayarId) {
            $this->db->prepare(
                "UPDATE pembayaran SET metode = 'QRIS', status = 'menunggu_konfirmasi' WHERE id = ?"
            )->execute([$bayarId]);
        } else {
            $this->db->prepare(
                "INSERT INTO pembayaran (pesanan_id, metode, jumlah, status, created_at)
                 VALUES (?, 'QRIS', ?, 'menunggu_konfirmasi', ?)"
            )->execute([$pesananId, $pesanan->total_harga, date('Y-m-d H:i:s')]);
        }

        $this->db->prepare(
            "UPDATE pesanan SET status = 'menunggu_konfirmasi' WHERE id = ?"
        )->execute([$pesananId]);

        flash('success', "Pembayaran QRIS pesanan {$pesanan->kode_pesanan} berhasil dikirim! Menunggu konfirmasi kasir.");
        redirect('/customer/pesanan-saya');
    }
}
