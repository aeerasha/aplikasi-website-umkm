<?php
class CustomerController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        requireRole('pelanggan');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function dashboard(): void {
        $uid = $_SESSION['user']['id'];

        $pesananSaya = $this->db->prepare(
            "SELECT ps.*, p.nama as nama_produk,
                    pb.id as bayar_id, pb.status as status_bayar
             FROM pesanan ps
             LEFT JOIN produk p ON p.id = ps.produk_id
             LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
             WHERE ps.user_id=? ORDER BY ps.created_at DESC LIMIT 5"
        );
        $pesananSaya->execute([$uid]);
        $pesananSaya = $pesananSaya->fetchAll();

        $totalPesanan = $this->db->prepare("SELECT COUNT(*) FROM pesanan WHERE user_id=?");
        $totalPesanan->execute([$uid]); $totalPesanan = $totalPesanan->fetchColumn();

        $totalBelanja = $this->db->prepare("SELECT COALESCE(SUM(total_harga),0) FROM pesanan WHERE user_id=? AND status='selesai'");
        $totalBelanja->execute([$uid]); $totalBelanja = $totalBelanja->fetchColumn();

        $totalUlasan = $this->db->prepare("SELECT COUNT(*) FROM ulasan WHERE user_id=?");
        $totalUlasan->execute([$uid]); $totalUlasan = $totalUlasan->fetchColumn();

        $tagihan = $this->db->prepare(
            "SELECT COUNT(*) FROM pesanan ps
             LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
             WHERE ps.user_id=? AND (pb.status='pending' OR pb.id IS NULL)
             AND ps.status != 'batal'"
        );
        $tagihan->execute([$uid]); $tagihan = $tagihan->fetchColumn();

        renderCustomer('customer/dashboard', compact(
            'pesananSaya','totalPesanan','totalBelanja','totalUlasan','tagihan'
        ));
    }

    // ── Katalog ───────────────────────────────────────────────────────────────
   public function katalog(): void {
        $search   = $_GET['search'] ?? '';
        $kategori = $_GET['kategori'] ?? ''; // Ini adalah NAMA kategori (misal: 'Makanan')

        // Query dengan JOIN agar bisa menyaring berdasarkan nama kategori
        $sql = "SELECT p.*, k.nama as kategori 
                FROM produk p 
                LEFT JOIN kategori k ON p.kategori_id = k.id 
                WHERE p.stok > 0";
        
        $params = [];

        if ($search) {
            $sql .= " AND p.nama LIKE ?";
            $params[] = "%$search%";
        }

        if ($kategori) {
            $sql .= " AND k.nama = ?"; // Menyaring berdasarkan nama kategori
            $params[] = $kategori;
        }

        $sql .= " ORDER BY p.nama";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $produks = $stmt->fetchAll();

        renderCustomer('customer/katalog', compact('produks', 'search', 'kategori'));
    }

    // ── Pesanan Saya ──────────────────────────────────────────────────────────
    public function pesananSaya(): void {
        $uid  = $_SESSION['user']['id'];
        $stmt = $this->db->prepare(
            "SELECT ps.*, p.nama as nama_produk,
                    pb.id as bayar_id, pb.status as status_bayar,
                    pb.metode as metode_bayar, pb.bukti_transfer
             FROM pesanan ps
             LEFT JOIN produk p ON p.id = ps.produk_id
             LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
             WHERE ps.user_id=? ORDER BY ps.created_at DESC"
        );
        $stmt->execute([$uid]);
        $pesanans = $stmt->fetchAll();
        renderCustomer('customer/pesanan', compact('pesanans'));
    }

    // ── Buat Pesanan ──────────────────────────────────────────────────────────
    public function buatPesanan(): void {
        $produks   = $this->db->query("SELECT * FROM produk WHERE stok > 0 ORDER BY nama")->fetchAll();
        $produk_id = (int)($_GET['produk_id'] ?? 0);
        renderCustomer('customer/buat_pesanan', compact('produks','produk_id'));
    }

    public function storePesanan(): void {
        $uid       = $_SESSION['user']['id'];
        $user      = $_SESSION['user'];
        $produk_id = (int)($_POST['produk_id'] ?? 0);
        $jumlah    = max(1, (int)($_POST['jumlah'] ?? 1));
        $harga     = 0;

        if ($produk_id) {
            $p = $this->db->prepare("SELECT harga, stok FROM produk WHERE id=?");
            $p->execute([$produk_id]);
            $row = $p->fetchObject();
            if ($row) {
                if ($row->stok < $jumlah) {
                    flash('error', 'Stok tidak mencukupi.');
                    redirect('/customer/buat-pesanan');
                }
                $harga = $row->harga * $jumlah;
            }
        }

        $n    = $this->db->query("SELECT COUNT(*)+1 FROM pesanan")->fetchColumn();
        $kode = 'ORD-' . str_pad($n, 4, '0', STR_PAD_LEFT);

        $stmt = $this->db->prepare(
            "INSERT INTO pesanan (kode_pesanan, user_id, nama_pelanggan, telepon, produk_id, jumlah, total_harga, status, catatan, created_at)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $kode, $uid, $user['nama'],
            trim($_POST['telepon'] ?? ''),
            $produk_id ?: null, $jumlah, $harga, 'pending',
            trim($_POST['catatan'] ?? ''),
            date('Y-m-d H:i:s')
        ]);

        $pesananId = $this->db->lastInsertId();

        flash('success', "Pesanan $kode berhasil dibuat! Silakan lakukan pembayaran.");
        redirect("/customer/bayar?pesanan_id=$pesananId");
    }

    // ── Halaman Pembayaran ────────────────────────────────────────────────────
    public function bayar(): void {
        $uid       = $_SESSION['user']['id'];
        $pesananId = (int)($_GET['pesanan_id'] ?? 0);

        // Ambil pesanan milik user ini
        $stmt = $this->db->prepare(
            "SELECT ps.*, p.nama as nama_produk
             FROM pesanan ps
             LEFT JOIN produk p ON p.id = ps.produk_id
             WHERE ps.id=? AND ps.user_id=?"
        );
        $stmt->execute([$pesananId, $uid]);
        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/customer/pesanan-saya');
        }

        // Cek apakah sudah ada pembayaran lunas
        $cek = $this->db->prepare("SELECT * FROM pembayaran WHERE pesanan_id=? AND status='lunas'");
        $cek->execute([$pesananId]);
        if ($cek->fetchObject()) {
            flash('error', 'Pesanan ini sudah lunas.');
            redirect('/customer/pesanan-saya');
        }

        // Ambil pembayaran pending jika ada
        $bayarAda = $this->db->prepare("SELECT * FROM pembayaran WHERE pesanan_id=?");
        $bayarAda->execute([$pesananId]);
        $bayarAda = $bayarAda->fetchObject();

        renderCustomer('customer/bayar', compact('pesanan','bayarAda'));
    }

    // ── Simpan Pembayaran ─────────────────────────────────────────────────────
    public function storeBayar(): void {
        $uid       = $_SESSION['user']['id'];
        $pesananId = (int)($_POST['pesanan_id'] ?? 0);
        $metode    = trim($_POST['metode'] ?? '');
        $catatan   = trim($_POST['catatan'] ?? '');

        // Validasi pesanan milik user
        $stmt = $this->db->prepare("SELECT * FROM pesanan WHERE id=? AND user_id=?");
        $stmt->execute([$pesananId, $uid]);
        $pesanan = $stmt->fetchObject();

        if (!$pesanan) {
            flash('error', 'Pesanan tidak valid.');
            redirect('/customer/pesanan-saya');
        }

        // Upload bukti transfer (opsional)
        $buktiFile = null;
        if (!empty($_FILES['bukti_transfer']['name'])) {
            $file    = $_FILES['bukti_transfer'];
            $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','pdf'];
            if (!in_array($ext, $allowed)) {
                flash('error', 'Format file bukti tidak valid (jpg/png/pdf).');
                redirect("/customer/bayar?pesanan_id=$pesananId");
            }
            if ($file['size'] > 2 * 1024 * 1024) {
                flash('error', 'Ukuran file maksimal 2MB.');
                redirect("/customer/bayar?pesanan_id=$pesananId");
            }
            $namaFile = 'bukti_' . $pesanan->kode_pesanan . '_' . time() . '.' . $ext;
            $tujuan   = BASE_PATH . '/public/uploads/bukti/' . $namaFile;
            if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
                // Jika upload gagal (misal di lingkungan demo), lanjutkan tanpa file
                $namaFile = null;
            }
            $buktiFile = $namaFile;
        }

        // Tentukan status pembayaran
        // Tunai = langsung "menunggu_konfirmasi", transfer = "menunggu_konfirmasi"
        $statusBayar = 'menunggu_konfirmasi';

        // Cek apakah sudah ada record pembayaran untuk pesanan ini
        $existing = $this->db->prepare("SELECT id FROM pembayaran WHERE pesanan_id=?");
        $existing->execute([$pesananId]);
        $existingId = $existing->fetchColumn();

        if ($existingId) {
            // Update yang sudah ada
            $upd = $this->db->prepare(
                "UPDATE pembayaran SET metode=?, jumlah=?, status=?, bukti_transfer=?, catatan=? WHERE id=?"
            );
            $upd->execute([$metode, $pesanan->total_harga, $statusBayar, $buktiFile, $catatan, $existingId]);
        } else {
            // Insert baru
            $ins = $this->db->prepare(
                "INSERT INTO pembayaran (pesanan_id, metode, jumlah, status, bukti_transfer, catatan, created_at) VALUES (?,?,?,?,?,?,?)"
            );
            $ins->execute([$pesananId, $metode, $pesanan->total_harga, $statusBayar, $buktiFile, $catatan, date('Y-m-d H:i:s')]);
        }

        flash('success', "Pembayaran untuk {$pesanan->kode_pesanan} berhasil dikirim! Menunggu konfirmasi admin.");
        redirect('/customer/pesanan-saya');
    }

    // ── Riwayat Pembayaran ────────────────────────────────────────────────────
    public function riwayatBayar(): void {
        $uid  = $_SESSION['user']['id'];
        $stmt = $this->db->prepare(
            "SELECT pb.*, ps.kode_pesanan, ps.nama_pelanggan, ps.total_harga as nilai_pesanan,
                    p.nama as nama_produk, ps.status as status_pesanan
             FROM pembayaran pb
             JOIN pesanan ps ON ps.id = pb.pesanan_id
             LEFT JOIN produk p ON p.id = ps.produk_id
             WHERE ps.user_id=?
             ORDER BY pb.created_at DESC"
        );
        $stmt->execute([$uid]);
        $riwayat = $stmt->fetchAll();
        renderCustomer('customer/riwayat_bayar', compact('riwayat'));
    }

    // ── Ulasan ────────────────────────────────────────────────────────────────
    public function ulasanSaya(): void {
        $uid  = $_SESSION['user']['id'];
        $stmt = $this->db->prepare(
            "SELECT u.*, p.nama as nama_produk FROM ulasan u
             LEFT JOIN produk p ON p.id=u.produk_id
             WHERE u.user_id=? ORDER BY u.created_at DESC"
        );
        $stmt->execute([$uid]);
        $ulasans = $stmt->fetchAll();
        renderCustomer('customer/ulasan', compact('ulasans'));
    }

    public function buatUlasan(): void {
        $produks = $this->db->query("SELECT id, nama FROM produk ORDER BY nama")->fetchAll();
        renderCustomer('customer/buat_ulasan', compact('produks'));
    }

    public function storeUlasan(): void {
        $uid  = $_SESSION['user']['id'];
        $nama = $_SESSION['user']['nama'];
        $stmt = $this->db->prepare(
            "INSERT INTO ulasan (user_id, nama_pelanggan, produk_id, rating, komentar) VALUES (?,?,?,?,?)"
        );
        $stmt->execute([
            $uid, $nama,
            (int)($_POST['produk_id'] ?? 0) ?: null,
            min(5, max(1, (int)($_POST['rating'] ?? 5))),
            trim($_POST['komentar'] ?? ''),
        ]);
        flash('success', 'Ulasan berhasil dikirim!');
        redirect('/customer/ulasan-saya');
    }

    // ── Profil ────────────────────────────────────────────────────────────────
    public function profile(): void {
        $uid  = $_SESSION['user']['id'];
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$uid]);
        $user = $stmt->fetchObject();
        renderCustomer('customer/profile', compact('user'));
    }

    public function updateProfile(): void {
        $uid = $_SESSION['user']['id'];
        $this->db->prepare("UPDATE users SET nama=?, telepon=?, alamat=? WHERE id=?")
                 ->execute([
                     trim($_POST['nama'] ?? ''),
                     trim($_POST['telepon'] ?? ''),
                     trim($_POST['alamat'] ?? ''),
                     $uid
                 ]);
        $_SESSION['user']['nama'] = trim($_POST['nama'] ?? '');
        flash('success', 'Profil berhasil diperbarui!');
        redirect('/customer/profile');
    }
}
