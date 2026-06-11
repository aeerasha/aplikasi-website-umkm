<?php
class PesananController {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); requireRole('admin'); }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $sql = "SELECT ps.*, p.nama as nama_produk FROM pesanan ps LEFT JOIN produk p ON p.id=ps.produk_id WHERE 1=1";
        $params = [];
        if ($search) { $sql .= " AND (ps.nama_pelanggan LIKE ? OR ps.kode_pesanan LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        if ($status) { $sql .= " AND ps.status=?"; $params[] = $status; }
        $sql .= " ORDER BY ps.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pesanans = $stmt->fetchAll();
        renderView('pesanan/index', compact('pesanans', 'search', 'status'));
    }

    public function create(): void {
        $produks = $this->db->query("SELECT id, nama, harga FROM produk ORDER BY nama")->fetchAll();
        renderView('pesanan/create', compact('produks'));
    }

    public function store(): void {
        $produk_id = (int)($_POST['produk_id'] ?? 0);
        $jumlah    = (int)($_POST['jumlah'] ?? 1);
        $harga = 0;
        if ($produk_id) {
            $p = $this->db->prepare("SELECT harga FROM produk WHERE id=?");
            $p->execute([$produk_id]);
            $row = $p->fetchObject();
            $harga = $row ? $row->harga * $jumlah : 0;
        }
        $kode = 'ORD-' . str_pad($this->db->query("SELECT COUNT(*)+1 as c FROM pesanan")->fetchObject()->c, 3, '0', STR_PAD_LEFT);
        $data = [
            'kode_pesanan'   => $kode,
            'nama_pelanggan' => trim($_POST['nama_pelanggan'] ?? ''),
            'telepon'        => trim($_POST['telepon'] ?? ''),
            'produk_id'      => $produk_id ?: null,
            'jumlah'         => $jumlah,
            'total_harga'    => $harga,
            'status'         => $_POST['status'] ?? 'pending',
            'catatan'        => trim($_POST['catatan'] ?? ''),
            'created_at'     => date('Y-m-d H:i:s'),
        ];
        if (empty($data['nama_pelanggan'])) { flash('error', 'Nama pelanggan wajib diisi.'); redirect('/pesanan/create'); }
        $stmt = $this->db->prepare("INSERT INTO pesanan (kode_pesanan, nama_pelanggan, telepon, produk_id, jumlah, total_harga, status, catatan, created_at) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute(array_values($data));
        flash('success', 'Pesanan berhasil ditambahkan!');
        redirect('/pesanan');
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM pesanan WHERE id=?");
        $stmt->execute([$id]);
        $pesanan = $stmt->fetchObject();
        if (!$pesanan) { flash('error', 'Pesanan tidak ditemukan.'); redirect('/pesanan'); }
        $produks = $this->db->query("SELECT id, nama, harga FROM produk ORDER BY nama")->fetchAll();
        renderView('pesanan/edit', compact('pesanan', 'produks'));
    }

    public function update(): void {
        $id        = (int)($_POST['id'] ?? 0);
        $produk_id = (int)($_POST['produk_id'] ?? 0);
        $jumlah    = (int)($_POST['jumlah'] ?? 1);
        $harga = 0;
        if ($produk_id) {
            $p = $this->db->prepare("SELECT harga FROM produk WHERE id=?");
            $p->execute([$produk_id]);
            $row = $p->fetchObject();
            $harga = $row ? $row->harga * $jumlah : 0;
        }
        $data = [
            'nama_pelanggan' => trim($_POST['nama_pelanggan'] ?? ''),
            'telepon'        => trim($_POST['telepon'] ?? ''),
            'produk_id'      => $produk_id ?: null,
            'jumlah'         => $jumlah,
            'total_harga'    => $harga,
            'status'         => $_POST['status'] ?? 'pending',
            'catatan'        => trim($_POST['catatan'] ?? ''),
        ];
        if (empty($data['nama_pelanggan'])) { flash('error', 'Nama pelanggan wajib diisi.'); redirect("/pesanan/edit?id=$id"); }
        $stmt = $this->db->prepare("UPDATE pesanan SET nama_pelanggan=?, telepon=?, produk_id=?, jumlah=?, total_harga=?, status=?, catatan=? WHERE id=?");
        $stmt->execute([...array_values($data), $id]);
        flash('success', 'Pesanan berhasil diperbarui!');
        redirect('/pesanan');
    }

    public function destroy(): void {
        $id = (int)($_POST['id'] ?? 0);
        $this->db->prepare("DELETE FROM pesanan WHERE id=?")->execute([$id]);
        flash('success', 'Pesanan berhasil dihapus!');
        redirect('/pesanan');
    }
}
