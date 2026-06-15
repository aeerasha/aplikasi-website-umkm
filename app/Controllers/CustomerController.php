<?php
class CustomerController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        requireCustomer(); // tidak perlu login akun — cukup sesi tamu
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function dashboard(): void {
        $customer = $_SESSION['customer'];

        $pesananSaya = $this->db->prepare(
            "SELECT ps.*, p.nama as nama_produk,
                    pb.id as bayar_id, pb.status as status_bayar
             FROM pesanan ps
             LEFT JOIN produk p ON p.id = ps.produk_id
             LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
             WHERE ps.telepon=? ORDER BY ps.created_at DESC LIMIT 5"
        );
        $pesananSaya->execute([$customer['no_hp']]);;
        $pesananSaya = $pesananSaya->fetchAll();

        $totalPesanan = $this->db->prepare("SELECT COUNT(*) FROM pesanan WHERE telepon=?");
       $totalPesanan->execute([$customer['no_hp']]); $totalPesanan = $totalPesanan->fetchColumn();

        $totalBelanja = $this->db->prepare("SELECT COALESCE(SUM(total_harga),0) FROM pesanan WHERE telepon=? AND status='selesai'");
        $totalBelanja->execute([$customer['no_hp']]); $totalBelanja = $totalBelanja->fetchColumn();

        $totalUlasan = $this->db->prepare("SELECT COUNT(*) FROM ulasan WHERE nama_pelanggan=?");
        $totalUlasan->execute([$customer['nama']]); $totalUlasan = $totalUlasan->fetchColumn();

        $tagihan = $this->db->prepare(
            "SELECT COUNT(*) FROM pesanan ps
             LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
             WHERE ps.telepon=? AND (pb.status='pending'
             OR pb.status='menunggu_konfirmasi'
             OR pb.id IS NULL)
             AND ps.status != 'batal'"
        );
        $tagihan->execute([$customer['no_hp']]); $tagihan = $tagihan->fetchColumn();

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
    public function pesananSaya(): void
    {
        $customer = $_SESSION['customer'];

        $stmt = $this->db->prepare(
            "SELECT
                ps.*,
                pb.id as bayar_id,
                pb.status as status_bayar,
                pb.metode as metode_bayar,
                pb.bukti_transfer,

                (
                    SELECT GROUP_CONCAT(
                        nama_produk || ' x' || jumlah,
                        ', '
                    )
                    FROM detail_pesanan dp
                    WHERE dp.pesanan_id = ps.id
                ) as daftar_produk

            FROM pesanan ps
            LEFT JOIN pembayaran pb
                ON pb.pesanan_id = ps.id
            WHERE ps.telepon = ?
            ORDER BY ps.created_at DESC"
        );

        $stmt->execute([
            $customer['no_hp']
        ]);

        $pesanans = $stmt->fetchAll();

        renderCustomer(
            'customer/pesanan',
            compact('pesanans')
        );
    }

    // ── Buat Pesanan ──────────────────────────────────────────────────────────
    public function buatPesanan(): void {
            $produks   = $this->db->query("SELECT * FROM produk WHERE stok > 0 ORDER BY nama")->fetchAll();
            $produk_id = (int)($_GET['produk_id'] ?? 0);
            renderCustomer('customer/buat_pesanan', compact('produks','produk_id'));
        }

    public function storePesanan(): void
{
    // Pastikan session aktif
    $customer = $_SESSION['customer'] ?? null;
    $keranjang = $_SESSION['keranjang'] ?? [];

    if (empty($keranjang) || !$customer) {
        flash('error', 'Keranjang atau data pelanggan tidak lengkap!');
        redirect('/customer/keranjang');
        return;
    }

    try {
        // Aktifkan mode exception agar error database langsung terlihat
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->beginTransaction();

        // 1. Hitung total harga
        $totalHarga = 0;
        foreach ($keranjang as $produk_id => $data) {
            $jumlah = is_array($data) ? $data['jumlah'] : $data;
            $stmt = $this->db->prepare("SELECT harga FROM produk WHERE id = ?");
            $stmt->execute([$produk_id]);
            $p = $stmt->fetchObject();
            if ($p) {
                $totalHarga += ($p->harga * $jumlah);
            } else {
                throw new Exception("Produk dengan ID $produk_id tidak ditemukan.");
            }
        }

        // 2. Insert ke table 'pesanan'
        $sqlPesanan = "INSERT INTO pesanan (nama_pelanggan, telepon, nomor_meja, total_harga, status, created_at) VALUES (?, ?, ?, ?, 'pending', ?)";
        $stmt = $this->db->prepare($sqlPesanan);
        $stmt->execute([
            $customer['nama'], 
            $customer['no_hp'], 
            $customer['nomor_meja'], 
            $totalHarga, 
            date('Y-m-d H:i:s')
        ]);
        
        $pesananId = $this->db->lastInsertId();
        $kode = 'ORD-' . str_pad($pesananId, 4, '0', STR_PAD_LEFT);
        
        $this->db->prepare("UPDATE pesanan SET kode_pesanan = ? WHERE id = ?")->execute([$kode, $pesananId]);

        // 3. Insert ke 'detail_pesanan'
        $sqlDetail = "INSERT INTO detail_pesanan (pesanan_id, produk_id, nama_produk, harga_satuan, jumlah, subtotal, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtDetail = $this->db->prepare($sqlDetail);
        
        foreach ($keranjang as $produk_id => $data) {
            $jumlah = is_array($data) ? $data['jumlah'] : $data;
            $catatan = is_array($data) ? ($data['catatan'] ?? '') : '';

            $p = $this->db->prepare("SELECT nama, harga FROM produk WHERE id = ?");
            $p->execute([$produk_id]);
            $prod = $p->fetchObject();
            
            if ($prod) {
                $subtotal = $prod->harga * $jumlah;
                $stmtDetail->execute([$pesananId, $produk_id, $prod->nama, $prod->harga, $jumlah, $subtotal, $catatan]);
                
                // Kurangi stok
                $this->db->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?")->execute([$jumlah, $produk_id]);
            }
        }

        $this->db->commit();
        unset($_SESSION['keranjang']); 
        flash('success', "Pesanan {$kode} berhasil dibuat!");
        redirect("/customer/pesanan-saya");

    } catch (Exception $e) {
        $this->db->rollBack();
        // Jika gagal, ini akan menampilkan pesan error yang spesifik
        die("Error Database saat checkout: " . $e->getMessage()); 
    }
}

    // ── Halaman Pembayaran ────────────────────────────────────────────────────
    public function bayar(): void {
        $customer = $_SESSION['customer'];
        $pesananId = (int)($_GET['pesanan_id'] ?? 0);

        // Ambil pesanan milik user ini
        $stmt = $this->db->prepare(
            "SELECT ps.*, p.nama as nama_produk
             FROM pesanan ps
             LEFT JOIN produk p ON p.id = ps.produk_id
             WHERE ps.id=? AND ps.telepon=?"
        );
        $stmt->execute([
            $pesananId,
            $customer['no_hp']
        ]);
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
        $customer = $_SESSION['customer'];
        $pesananId = (int)($_POST['pesanan_id'] ?? 0);
        $metode    = trim($_POST['metode'] ?? '');
        $catatan   = trim($_POST['catatan'] ?? '');

        // Validasi pesanan milik user
        $stmt = $this->db->prepare(
            "SELECT * FROM pesanan
            WHERE id=?
            AND telepon=?"
        );

        $stmt->execute([
            $pesananId,
            $customer['no_hp']
        ]);

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
    public function riwayatBayar(): void
    {
        $customer = $_SESSION['customer'];

        $stmt = $this->db->prepare(
            "SELECT
                pb.*,
                ps.kode_pesanan,
                ps.nama_pelanggan,
                ps.total_harga AS nilai_pesanan,
                ps.status AS status_pesanan

            FROM pembayaran pb
            JOIN pesanan ps
                ON ps.id = pb.pesanan_id

            WHERE ps.telepon = ?

            ORDER BY pb.created_at DESC"
        );

        $stmt->execute([
            $customer['no_hp']
        ]);

        $riwayat = $stmt->fetchAll();

        renderCustomer(
            'customer/riwayat_bayar',
            compact('riwayat')
        );
    }

    // ── Ulasan ────────────────────────────────────────────────────────────────
    public function ulasanSaya(): void {
        $customer = $_SESSION['customer'];
        $stmt = $this->db->prepare(
            "SELECT u.*, p.nama as nama_produk FROM ulasan u
             LEFT JOIN produk p ON p.id=u.produk_id
             WHERE u.nama_pelanggan=? ORDER BY u.created_at DESC"
        );
        $stmt->execute([
            $customer['nama']
        ]);
        $ulasans = $stmt->fetchAll();
        renderCustomer('customer/ulasan', compact('ulasans'));
    }

    public function buatUlasan(): void {
        $produks = $this->db->query("SELECT id, nama FROM produk ORDER BY nama")->fetchAll();
        renderCustomer('customer/buat_ulasan', compact('produks'));
    }

    public function storeUlasan(): void {
        $customer = $_SESSION['customer'];

        $nama = $customer['nama'];
        $stmt = $this->db->prepare(
            "INSERT INTO ulasan
                (
                user_id,
                nama_pelanggan,
                produk_id,
                rating,
                komentar
                ) VALUES (?,?,?,?,?)"
        );
        $stmt->execute([
            null, $nama,
            (int)($_POST['produk_id'] ?? 0) ?: null,
            min(5, max(1, (int)($_POST['rating'] ?? 5))),
            trim($_POST['komentar'] ?? ''),
        ]);
        flash('success', 'Ulasan berhasil dikirim!');
        redirect('/customer/ulasan-saya');
    }

    // ── Profil ────────────────────────────────────────────────────────────────
    public function profile(): void {
        $customer = $_SESSION['customer'];
        renderCustomer(
            'customer/profile',
            compact('customer')
        );
    }

    public function updateProfile(): void {
        $_SESSION['customer']['nama'] = trim($_POST['nama']);
        $_SESSION['customer']['no_hp'] = trim($_POST['telepon']);
        flash('success', 'Profil berhasil diperbarui!');
        redirect('/customer/profile');
    }

    // ── Identitas Tamu (GET) — tampilkan form, bypass requireCustomer ────────
    // CATATAN: method ini dipanggil LANGSUNG dari index.php tanpa new CustomerController()
    // karena __construct memanggil requireCustomer(). Lihat route di index.php.

    // ── Keranjang Belanja ─────────────────────────────────────────────────────

    public function keranjang(): void {
    $items = $_SESSION['keranjang'] ?? [];
    $produkData = [];
    $total = 0;

    foreach ($items as $produk_id => $data) {
        // Ambil jumlah dan catatan dengan aman
        $jumlah = is_array($data) ? $data['jumlah'] : $data;
        $catatan = is_array($data) ? ($data['catatan'] ?? '') : '';

        $stmt = $this->db->prepare("SELECT id, nama, harga, gambar FROM produk WHERE id = ?");
        $stmt->execute([(int)$produk_id]);
        $p = $stmt->fetchObject();
        
        if ($p) {
            $p->jumlah = $jumlah;
            $p->catatan = $catatan; // Untuk ditampilkan di view
            $p->subtotal = $p->harga * $jumlah;
            $total += $p->subtotal;
            $produkData[] = $p;
        }
    }
    renderCustomer('customer/keranjang', compact('produkData', 'total'));
}

    public function tambahKeranjang(): void {
        $produk_id = (int)($_POST['produk_id'] ?? 0);
        $jumlah = (int)($_POST['jumlah'] ?? 1); 

        $p = $this->db->prepare("SELECT id, stok FROM produk WHERE id = ?");
        $p->execute([$produk_id]);
        $produk = $p->fetchObject();

        if ($produk) {
            $keranjang = $_SESSION['keranjang'] ?? [];
            
            // Ambil catatan lama dengan aman
            $catatanLama = (isset($keranjang[$produk_id]) && is_array($keranjang[$produk_id])) 
                           ? $keranjang[$produk_id]['catatan'] : '';

            if ($jumlah <= 0) {
                unset($keranjang[$produk_id]);
            } elseif ($jumlah <= $produk->stok) {
                $keranjang[$produk_id] = [
                    'jumlah' => $jumlah,
                    'catatan' => $catatanLama
                ];
            } else {
                flash('error', 'Stok tidak mencukupi!');
            }
            $_SESSION['keranjang'] = $keranjang;
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function hapusKeranjang(): void {
        $produk_id = (int)($_POST['produk_id'] ?? 0);
        unset($_SESSION['keranjang'][$produk_id]);
        flash('success', 'Item dihapus dari keranjang.');
        redirect('/customer/keranjang');
    }

    public function kosongkanKeranjang(): void {
        unset($_SESSION['keranjang']);
        flash('success', 'Keranjang dikosongkan.');
        error_log("Isi Keranjang saat ini: " . print_r($_SESSION['keranjang'], true));
        redirect('/customer/keranjang');
    }

    public function updateCatatan(): void {
        $produk_id = (int)$_POST['produk_id'];
        $_SESSION['keranjang'][$produk_id]['catatan'] = $_POST['catatan'] ?? '';
    }

    public function checkout(): void {
    $items = $_SESSION['keranjang'] ?? [];
    if (empty($items)) {
        flash('error', 'Keranjang kosong!');
        redirect('/customer/keranjang');
    }

    $produkData = [];
    $total = 0;
    foreach ($items as $produk_id => $data) {
        $jumlah = is_array($data) ? $data['jumlah'] : $data;
        $catatan = is_array($data) ? ($data['catatan'] ?? '') : '';
        
        $p = $this->db->prepare("SELECT id, nama, harga FROM produk WHERE id = ?");
        $p->execute([$produk_id]);
        $prod = $p->fetchObject();
        
        if ($prod) {
            $prod->jumlah = $jumlah;
            $prod->catatan = $catatan;
            $prod->subtotal = $prod->harga * $jumlah;
            $total += $prod->subtotal;
            $produkData[] = $prod;
        }
    }
    renderCustomer('customer/checkout', compact('produkData', 'total'));
}
}

