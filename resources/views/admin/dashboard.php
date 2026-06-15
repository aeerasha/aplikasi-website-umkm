<?php $pageTitle = 'Dashboard Admin'; ?>
<style>
    .stat-card {
        padding: 1.25rem;
        border-radius: 12px;
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .stat-card .num { font-size: 1.4rem; font-weight: 700; margin-bottom: 2px; }
    .stat-card .lbl { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; }
</style>

<div class="page-header mb-4">
    <h4><i class="bi bi-speedometer2 me-2" style="color:var(--accent)"></i>Dashboard Admin</h4>
    <small class="text-muted">Selamat datang, <strong><?= e(auth()['nama']) ?></strong> 👋</small>
</div>

<?php if (!empty($menungguKonfirmasi) && $menungguKonfirmasi > 0): ?>
<div class="alert border-0 mb-4 d-flex align-items-center gap-3" style="background:#fef3c7;border-radius:12px">
    <i class="bi bi-bell-fill fs-4" style="color:#d97706"></i>
    <div>
        <strong style="color:#92400e"><?= $menungguKonfirmasi ?> pembayaran pelanggan menunggu konfirmasi!</strong>
        <div style="font-size:.83rem;color:#b45309">Segera verifikasi agar pesanan dapat diproses.</div>
    </div>
    <a href="/pembayaran?status=menunggu_konfirmasi" class="btn btn-sm ms-auto fw-bold" style="background:#f59e0b;color:#fff;border-radius:8px;white-space:nowrap">
        <i class="bi bi-check2-circle me-1"></i>Konfirmasi
    </a>
</div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="num"><?= $totalProduk ?></div><div class="lbl">Produk</div></div>
                <i class="bi bi-box-seam fs-3 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="num"><?= $totalPegawai ?></div><div class="lbl">Pegawai</div></div>
                <i class="bi bi-people fs-3 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="num" style="font-size: 1.1rem;"><?= formatRupiah($totalPemasukan) ?></div>
                    <div class="lbl">Total Pemasukan</div>
                </div>
                <i class="bi bi-cash-stack fs-3 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-trophy me-2 text-warning"></i>Produk Terlaris</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                    <?php foreach ($produkTerlaris as $i => $p): ?>
                    <tr>
                        <td class="ps-3" width="30"><span class="badge" style="background:<?= ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6'][$i] ?>"><?= $i+1 ?></span></td>
                        <td><?= e($p->nama) ?></td>
                        <td class="text-end pe-3"><strong><?= $p->total ?> pesanan</strong></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Stok Kritis</span>
                <a href="/stok" class="btn btn-sm btn-outline-primary" style="font-size:.75rem">Kelola</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($stokRendah)): ?>
                <div class="text-center p-4 text-muted"><i class="bi bi-check-circle text-success fs-3"></i><br>Semua stok aman</div>
                <?php else: ?>
                <table class="table mb-0">
                    <thead><tr><th class="ps-3">Bahan</th><th>Stok</th><th>Min</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($stokRendah as $s): ?>
                    <tr><td class="ps-3"><?= e($s->nama_bahan) ?></td><td class="text-danger fw-bold"><?= $s->jumlah ?> <?= e($s->satuan) ?></td><td><?= $s->stok_minimum ?></td><td><span class="badge bg-danger">Kritis</span></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</span>
        <a href="/pesanan" class="btn btn-sm btn-outline-primary" style="font-size:.75rem">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th class="ps-3">Kode</th><th>Pelanggan</th><th>Produk</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($pesananTerbaru as $p): ?>
            <tr>
                <td class="ps-3"><strong><?= e($p->kode_pesanan) ?></strong></td>
                <td><?= e($p->nama_pelanggan) ?></td>
                <td><?= e($p->nama_produk??'-') ?></td>
                <td><?= formatRupiah($p->total_harga) ?></td>
                <td><?= statusBadge($p->status) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>