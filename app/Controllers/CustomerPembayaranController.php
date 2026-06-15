<?php
class CustomerPembayaranController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function tampilkanBayar(): void {
        $total = 0;
        if (!empty($_SESSION['keranjang'])) {
            foreach ($_SESSION['keranjang'] as $produk_id => $data) {
                $jumlah = is_array($data) ? ($data['jumlah'] ?? 0) : $data;
                $p = $this->db->prepare("SELECT harga FROM produk WHERE id = ?");
                $p->execute([$produk_id]);
                $prod = $p->fetchObject();
                if ($prod && $jumlah > 0) {
                    $total += ($prod->harga * $jumlah);
                }
            }
        }
        renderCustomer('customer/qris', compact('total'));
    }

    public function finalStore(): void {
    $keranjang = $_SESSION['keranjang'] ?? [];
    if (empty($keranjang)) {
        flash('error', 'Keranjang Anda kosong.');
        redirect('/customer/katalog');
        return;
    }

    $nama    = $_POST['nama_pelanggan'] ?? $_SESSION['customer']['nama'] ?? 'Tamu';
    $telepon = $_POST['telepon'] ?? $_SESSION['customer']['no_hp'] ?? '-';
    $meja    = $_POST['nomor_meja'] ?? $_SESSION['customer']['nomor_meja'] ?? '0';

    try {
        $this->db->beginTransaction();

        // 1. Simpan ke 'pesanan'
        $kode = 'INV-' . time();
        $stmt = $this->db->prepare("INSERT INTO pesanan (kode_pesanan, total_harga, status, nama_pelanggan, telepon, nomor_meja) VALUES (?, 0, 'pending', ?, ?, ?)");
        $stmt->execute([$kode, $nama, $telepon, $meja]);
        $pesananId = $this->db->lastInsertId();

        // 2. Loop untuk simpan ke 'detail_pesanan' & hitung total harga
        $total = 0;
        $stmtDetail = $this->db->prepare("INSERT INTO detail_pesanan (pesanan_id, produk_id, nama_produk, harga_satuan, jumlah, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($keranjang as $id => $data) {
            $jumlah = is_array($data) ? $data['jumlah'] : $data;
            $p = $this->db->prepare("SELECT nama, harga FROM produk WHERE id = ?");
            $p->execute([$id]);
            $prod = $p->fetchObject();

            if ($prod) {
                $subtotal = $prod->harga * $jumlah;
                $total += $subtotal;
                $stmtDetail->execute([$pesananId, $id, $prod->nama, $prod->harga, $jumlah, $subtotal]);
            }
        }

        // 3. Update total_harga di tabel pesanan yang tadi masih 0
        $this->db->prepare("UPDATE pesanan SET total_harga = ? WHERE id = ?")->execute([$total, $pesananId]);

        // 4. Simpan ke 'pembayaran'
        $stmtBayar = $this->db->prepare("INSERT INTO pembayaran (pesanan_id, metode, jumlah, status, catatan, created_at) VALUES (?, 'QRIS', ?, 'lunas', 'Pembayaran via QRIS', ?)");
        $stmtBayar->execute([$pesananId, $total, date('Y-m-d H:i:s')]);

        $this->db->commit();
        
        unset($_SESSION['keranjang']);
        unset($_SESSION['customer']);
        flash('success', 'Pesanan berhasil dibuat!');

        // Redirect ke status pesanan dengan ID yang benar
        header("Location: /index.php?url=customer/pesanan-status&id=" . $pesananId);
        exit;

    } catch (Exception $e) {
        $this->db->rollBack();
        die('Database Error: ' . $e->getMessage());
    }
}

    // Di dalam class CustomerPembayaranController
    public function showStatus(int $id): void {
        // Pastikan query ini ada dan $id sudah benar diterima
        $stmt = $this->db->prepare("SELECT * FROM pesanan WHERE id = ?");
        $stmt->execute([$id]);
        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            die("Pesanan dengan ID $id tidak ditemukan.");
        }
        
        // Tampilkan view
        renderCustomer('customer/pesanan_status', compact('pesanan'));
    }
}