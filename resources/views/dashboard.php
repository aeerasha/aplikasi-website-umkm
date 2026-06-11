<?php
$db = Database::getInstance();
$totalProduk     = $db->query("SELECT COUNT(*) as c FROM produk")->fetchObject()->c;
$totalPegawai    = $db->query("SELECT COUNT(*) as c FROM pegawai")->fetchObject()->c;
$totalPesanan    = $db->query("SELECT COUNT(*) as c FROM pesanan")->fetchObject()->c;
$totalPembayaran = $db->query("SELECT SUM(jumlah) as t FROM pembayaran WHERE status='lunas'")->fetchObject()->t ?? 0;
$produkTerlaris  = $db->query("SELECT p.nama, COUNT(ps.id) as total FROM pesanan ps JOIN produk p ON p.id=ps.produk_id GROUP BY ps.produk_id ORDER BY total DESC LIMIT 5")->fetchAll();
$stokRendah      = $db->query("SELECT * FROM stok WHERE jumlah <= stok_minimum ORDER BY jumlah ASC LIMIT 5")->fetchAll();
$pesananTerbaru  = $db->query("SELECT ps.*, p.nama as nama_produk FROM pesanan ps LEFT JOIN produk p ON p.id=ps.produk_id ORDER BY ps.created_at DESC LIMIT 6")->fetchAll();
$pageTitle = 'Dashboard';
?>
<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="num"><?= $totalProduk ?></div><div class="lbl">Total Produk</div></div>
                <i class="bi bi-box-seam icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="num"><?= $totalPegawai ?></div><div class="lbl">Total Pegawai</div></div>
                <i class="bi bi-people icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="num"><?= $totalPesanan ?></div><div class="lbl">Total Pesanan</div></div>
                <i class="bi bi-receipt icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="num" style="font-size:1.2rem"><?= formatRupiah($totalPembayaran) ?></div><div class="lbl">Total Pemasukan</div></div>
                <i class="bi bi-cash-stack icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Produk Terlaris -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-trophy me-2 text-warning"></i>Produk Terlaris</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th class="ps-3">Produk</th><th class="text-center">Pesanan</th></tr></thead>
                    <tbody>
                    <?php foreach ($produkTerlaris as $i => $p): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge me-2" style="background:<?= ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6'][$i] ?>"><?= $i+1 ?></span>
                            <?= e($p->nama) ?>
                        </td>
                        <td class="text-center"><strong><?= $p->total ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stok Rendah -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Stok Bahan Baku Rendah</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($stokRendah)): ?>
                <div class="text-center p-4 text-muted"><i class="bi bi-check-circle fs-3 text-success"></i><br>Semua stok aman</div>
                <?php else: ?>
                <table class="table mb-0">
                    <thead><tr><th class="ps-3">Bahan</th><th>Stok</th><th>Min</th></tr></thead>
                    <tbody>
                    <?php foreach ($stokRendah as $s): ?>
                    <tr>
                        <td class="ps-3"><?= e($s->nama_bahan) ?></td>
                        <td><span class="text-danger fw-bold"><?= $s->jumlah ?> <?= e($s->satuan) ?></span></td>
                        <td><?= $s->stok_minimum ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pesanan Terbaru -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</span>
        <a href="/pesanan" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th class="ps-3">Kode</th><th>Pelanggan</th><th>Produk</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($pesananTerbaru as $p): ?>
                <tr>
                    <td class="ps-3"><strong><?= e($p->kode_pesanan) ?></strong></td>
                    <td><?= e($p->nama_pelanggan) ?></td>
                    <td><?= e($p->nama_produk ?? '-') ?></td>
                    <td><?= formatRupiah($p->total_harga) ?></td>
                    <td><?= statusBadge($p->status) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
