<?php $pageTitle = 'Beranda'; ?>
<div class="mb-4">
    <h4 class="fw-800 mb-0" style="color:#1a1f36">
        <i class="bi bi-house me-2" style="color:#10b981"></i>Halo, <?= e(auth()['nama']) ?>! 👋
    </h4>
    <small class="text-muted">Selamat berbelanja di UMKM App</small>
</div>

<?php if (($tagihan ?? 0) > 0): ?>
<div class="alert border-0 mb-4 d-flex align-items-center gap-3" style="background:#fef3c7;border-radius:12px">
    <i class="bi bi-exclamation-triangle-fill fs-4" style="color:#d97706"></i>
    <div>
        <strong style="color:#92400e">Anda memiliki <?= $tagihan ?> pesanan yang belum dibayar!</strong>
        <div style="font-size:.85rem;color:#b45309">Segera lakukan pembayaran agar pesanan dapat diproses.</div>
    </div>
    <a href="/customer/pesanan-saya" class="btn btn-sm ms-auto" style="background:#f59e0b;color:#fff;border-radius:8px">Bayar Sekarang</a>
</div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669)">
            <div class="num"><?= $totalPesanan ?></div><div class="lbl">Total Pesanan</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <div class="num" style="font-size:.95rem;margin-top:6px"><?= formatRupiah($totalBelanja) ?></div><div class="lbl">Total Belanja</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
            <div class="num"><?= $totalUlasan ?></div><div class="lbl">Ulasan</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <?php $menus = [
        ['/customer/katalog',       'bi-grid',        'Katalog',        'Lihat semua produk',    '#10b981'],
        ['/customer/buat-pesanan',  'bi-cart-plus',   'Pesan Sekarang', 'Buat pesanan baru',     '#6366f1'],
        ['/customer/pesanan-saya',  'bi-receipt',     'Pesanan Saya',   'Lacak status pesanan',  '#f59e0b'],
        ['/customer/riwayat-bayar', 'bi-credit-card', 'Riwayat Bayar',  'Cek status pembayaran', '#ef4444'],
        ['/customer/ulasan',   'bi-star',        'Ulasan',    'Kelola ulasan',         '#8b5cf6'],
        ['/customer/profile',       'bi-person',      'Profil',         'Edit data diri',        '#0ea5e9'],
    ]; ?>
    <?php foreach ($menus as [$url,$icon,$label,$sub,$color]): ?>
    <div class="col-6 col-md-4">
        <a href="<?= $url ?>" class="text-decoration-none">
            <div class="card p-3 h-100 text-center" style="border:2px solid #e5e7eb;transition:all .2s"
                 onmouseover="this.style.borderColor='<?= $color ?>';this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.borderColor='#e5e7eb';this.style.transform='none'">
                <i class="bi <?= $icon ?> fs-2 mb-2" style="color:<?= $color ?>"></i>
                <div class="fw-bold text-dark" style="font-size:.9rem"><?= $label ?></div>
                <small class="text-muted" style="font-size:.75rem"><?= $sub ?></small>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2 text-success"></i>Pesanan Terbaru</span>
        <a href="/customer/pesanan-saya" class="btn btn-sm btn-success" style="font-size:.75rem">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>
                <th class="ps-3">Kode</th><th>Produk</th><th>Total</th><th>Status</th><th>Bayar</th>
            </tr></thead>
            <tbody>
            <?php if (empty($pesananSaya)): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">
                    Belum ada pesanan. <a href="/customer/katalog" class="text-success">Mulai belanja!</a>
                </td></tr>
            <?php else: ?>
            <?php foreach ($pesananSaya as $p):
                $statusBayar = $p->status_bayar ?? 'belum';
                $bayarColor  = match($statusBayar) {
                    'lunas'               => 'success',
                    'menunggu_konfirmasi' => 'warning',
                    default               => 'danger',
                };
                $bayarLabel  = match($statusBayar) {
                    'lunas'               => 'Lunas',
                    'menunggu_konfirmasi' => 'Menunggu',
                    default               => 'Belum Bayar',
                };
            ?>
            <tr>
                <td class="ps-3"><strong><?= e($p->kode_pesanan) ?></strong></td>
                <td><?= e($p->nama_produk ?? '-') ?></td>
                <td><?= formatRupiah($p->total_harga) ?></td>
                <td><?= statusBadge($p->status) ?></td>
                <td>
                    <?php if ($statusBayar !== 'lunas' && $p->status !== 'batal'): ?>
                        <a href="/customer/bayar?pesanan_id=<?= $p->id ?>" class="btn btn-sm btn-success" style="font-size:.75rem">
                            <i class="bi bi-credit-card me-1"></i>Bayar
                        </a>
                    <?php else: ?>
                        <span class="badge bg-<?= $bayarColor ?>"><?= $bayarLabel ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
