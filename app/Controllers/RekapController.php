<?php

class RekapController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        requireRole('admin');
    }

    public function index(): void {
        $periode = $_GET['periode'] ?? 'harian';
        $bulan   = $_GET['bulan']   ?? date('Y-m');
        $minggu  = $_GET['minggu']  ?? date('Y-W');
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');

        switch ($periode) {
            case 'mingguan': $data = $this->getMingguan($minggu);  break;
            case 'bulanan':  $data = $this->getBulanan($bulan);    break;
            default:         $data = $this->getHarian($tanggal);   break;
        }

        $ringkasan     = $this->getRingkasanPeriode($periode, $tanggal, $minggu, $bulan);
        $grafikBulanan = $this->getGrafikBulanan();
        $metodeChart   = $this->getMetodePembayaran();

        renderView('admin/rekap/index', compact(
            'periode','tanggal','minggu','bulan',
            'data','ringkasan','grafikBulanan','metodeChart'
        ));
    }

    // ── Harian ───────────────────────────────────────────────────────────────
    private function getHarian(string $tanggal): array {
        $stmt = $this->db->prepare("
            SELECT
                ps.kode_pesanan,
                ps.nama_pelanggan,
                p.nama        AS nama_produk,
                k.nama        AS nama_kategori,
                ps.jumlah,
                ps.total_harga,
                ps.status,
                pb.metode,
                pb.status     AS status_bayar,
                strftime('%H:%M', ps.created_at) AS jam
            FROM pesanan ps
            LEFT JOIN produk p      ON p.id  = ps.produk_id
            LEFT JOIN kategori k    ON k.id  = p.kategori_id
            LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
            WHERE DATE(ps.created_at) = ?
            ORDER BY ps.created_at ASC
        ");
        $stmt->execute([$tanggal]);
        return $stmt->fetchAll();
    }

    // ── Mingguan ─────────────────────────────────────────────────────────────
    private function getMingguan(string $minggu): array {
        $stmt = $this->db->prepare("
            SELECT
                DATE(ps.created_at)  AS tanggal,
                COUNT(ps.id)         AS total_transaksi,
                SUM(ps.total_harga)  AS total_penjualan,
                SUM(CASE WHEN pb.status='lunas' THEN pb.jumlah ELSE 0 END) AS total_diterima,
                COUNT(CASE WHEN ps.status='selesai' THEN 1 END) AS selesai,
                COUNT(CASE WHEN ps.status='batal'   THEN 1 END) AS batal
            FROM pesanan ps
            LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
            WHERE strftime('%Y-%W', ps.created_at) = ?
            GROUP BY DATE(ps.created_at)
            ORDER BY tanggal ASC
        ");
        $stmt->execute([$minggu]);
        return $stmt->fetchAll();
    }

    // ── Bulanan ──────────────────────────────────────────────────────────────
    private function getBulanan(string $bulan): array {
        $stmt = $this->db->prepare("
            SELECT
                strftime('%Y-%W', ps.created_at) AS minggu_ke,
                strftime('%d', MIN(ps.created_at)) || '-' ||
                strftime('%d', MAX(ps.created_at)) AS rentang,
                COUNT(ps.id)        AS total_transaksi,
                SUM(ps.total_harga) AS total_penjualan,
                SUM(CASE WHEN pb.status='lunas' THEN pb.jumlah ELSE 0 END) AS total_diterima,
                COUNT(CASE WHEN ps.status='selesai' THEN 1 END) AS selesai,
                COUNT(CASE WHEN ps.status='batal'   THEN 1 END) AS batal
            FROM pesanan ps
            LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
            WHERE strftime('%Y-%m', ps.created_at) = ?
            GROUP BY strftime('%Y-%W', ps.created_at)
            ORDER BY minggu_ke ASC
        ");
        $stmt->execute([$bulan]);
        return $stmt->fetchAll();
    }

    // ── Ringkasan periode — prepared statement, tidak ada string interpolasi ──
    private function getRingkasanPeriode(
        string $periode,
        string $tgl,
        string $minggu,
        string $bulan
    ): object {
        [$filterExpr, $param] = match($periode) {
            'mingguan' => ["strftime('%Y-%W', ps.created_at) = ?", $minggu],
            'bulanan'  => ["strftime('%Y-%m', ps.created_at) = ?",  $bulan],
            default    => ["DATE(ps.created_at) = ?",               $tgl],
        };

        $stmt = $this->db->prepare("
            SELECT
                COUNT(ps.id)                                                      AS total_transaksi,
                COALESCE(SUM(ps.total_harga), 0)                                 AS total_penjualan,
                COALESCE(SUM(CASE WHEN pb.status = 'lunas'
                                  THEN pb.jumlah ELSE 0 END), 0)                 AS total_diterima,
                COUNT(CASE WHEN ps.status = 'selesai'             THEN 1 END)    AS pesanan_selesai,
                COUNT(CASE WHEN ps.status = 'batal'               THEN 1 END)    AS pesanan_batal,
                COUNT(CASE WHEN ps.status = 'pending'             THEN 1 END)    AS pesanan_pending,
                COUNT(CASE WHEN ps.status = 'menunggu_konfirmasi' THEN 1 END)    AS pesanan_menunggu,
                COALESCE(AVG(ps.total_harga), 0)                                 AS rata_rata
            FROM pesanan ps
            LEFT JOIN pembayaran pb ON pb.pesanan_id = ps.id
            WHERE $filterExpr
        ");
        $stmt->execute([$param]);
        $ringkasan = $stmt->fetchObject();

        // Produk terlaris: coba dari detail_pesanan dulu, fallback ke produk_id lama
        $ringkasan->produk_terlaris = $this->getProdukTerlarisLabel($filterExpr, $param);

        return $ringkasan;
    }

    // ── Produk terlaris — SUM dari detail_pesanan, fallback ke COUNT pesanan ──
    private function getProdukTerlarisLabel(string $filterExpr, string $param): string {
        // Cek apakah tabel detail_pesanan sudah ada
        $tableExists = $this->db->query(
            "SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='detail_pesanan'"
        )->fetchColumn();

        if ($tableExists) {
            $stmt = $this->db->prepare("
                SELECT pr.nama
                FROM detail_pesanan dp
                JOIN produk  pr ON pr.id = dp.produk_id
                JOIN pesanan ps ON ps.id = dp.pesanan_id
                WHERE $filterExpr
                GROUP BY dp.produk_id
                ORDER BY SUM(dp.jumlah) DESC
                LIMIT 1
            ");
            $stmt->execute([$param]);
            $nama = $stmt->fetchColumn();
            if ($nama) return $nama;
        }

        // Fallback: hitung dari kolom produk_id di tabel pesanan (data lama)
        $stmt = $this->db->prepare("
            SELECT pr.nama
            FROM pesanan ps
            JOIN produk pr ON pr.id = ps.produk_id
            WHERE $filterExpr AND ps.produk_id IS NOT NULL
            GROUP BY ps.produk_id
            ORDER BY COUNT(*) DESC
            LIMIT 1
        ");
        $stmt->execute([$param]);
        return $stmt->fetchColumn() ?: '-';
    }

    // ── Grafik 12 bulan terakhir ──────────────────────────────────────────────
    private function getGrafikBulanan(): array {
        return $this->db->query("
            SELECT
                strftime('%Y-%m', created_at) AS bulan,
                COUNT(*)                      AS transaksi,
                COALESCE(SUM(total_harga), 0)  AS penjualan
            FROM pesanan
            WHERE created_at >= DATE('now', 'start of month', '-11 months')
            GROUP BY strftime('%Y-%m', created_at)
            ORDER BY bulan ASC
        ")->fetchAll();
    }

    // ── Metode pembayaran ─────────────────────────────────────────────────────
    private function getMetodePembayaran(): array {
        return $this->db->query("
            SELECT metode, COUNT(*) AS jml, COALESCE(SUM(jumlah), 0) AS total
            FROM pembayaran
            WHERE status = 'lunas'
            GROUP BY metode
            ORDER BY total DESC
        ")->fetchAll();
    }

    // ── Export CSV ────────────────────────────────────────────────────────────
    public function export(): void {
        $periode = $_GET['periode'] ?? 'harian';
        $bulan   = $_GET['bulan']   ?? date('Y-m');
        $minggu  = $_GET['minggu']  ?? date('Y-W');
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');

        $label = match($periode) {
            'mingguan' => "minggu-$minggu",
            'bulanan'  => "bulan-$bulan",
            default    => "harian-$tanggal",
        };

        header('Content-Type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"rekap-penjualan-$label.csv\"");
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); 

        if ($periode === 'harian') {
            fputcsv($out, ['Kode Pesanan','Pelanggan','Produk','Kategori','Jumlah','Total','Status Pesanan','Metode Bayar','Status Bayar','Jam']);
            foreach ($this->getHarian($tanggal) as $r) {
                fputcsv($out, [$r->kode_pesanan,$r->nama_pelanggan,$r->nama_produk,$r->nama_kategori,$r->jumlah,$r->total_harga,$r->status,$r->metode,$r->status_bayar,$r->jam]);
            }
        } else {
            fputcsv($out, ['Periode','Total Transaksi','Total Penjualan','Total Diterima','Selesai','Batal']);
            $rows = $periode === 'bulanan' ? $this->getBulanan($bulan) : $this->getMingguan($minggu);
            foreach ($rows as $r) {
                $p = $r->tanggal ?? ("Minggu ke-" . $r->minggu_ke);
                fputcsv($out, [$p,$r->total_transaksi,$r->total_penjualan,$r->total_diterima,$r->selesai,$r->batal]);
            }
        }

        fclose($out);
        exit;
    }
}