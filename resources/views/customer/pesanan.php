<?php $pageTitle = 'Pesanan Saya'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-800 mb-0" style="color:#1a1f36"><i class="bi bi-receipt me-2" style="color:#10b981"></i>Pesanan Saya</h4>
        <small class="text-muted">Lacak dan bayar semua pesanan Anda</small>
    </div>
    <a href="/customer/buat-pesanan" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Buat Pesanan</a>
</div>

<?php if (empty($pesanans)): ?>
<div class="card text-center p-5">
    <i class="bi bi-receipt fs-1 text-muted mb-3"></i>
    <h5 class="text-muted">Belum ada pesanan</h5>
    <a href="/customer/katalog" class="btn btn-success mx-auto mt-2" style="width:fit-content">
        <i class="bi bi-grid me-1"></i>Lihat Katalog
    </a>
</div>
<?php else: ?>
<div class="row g-3">
<?php foreach ($pesanans as $p):
    $statusBayar = $p->status_bayar ?? null;
    $sudahBayar  = in_array($statusBayar, ['lunas','menunggu_konfirmasi']);
    $steps = ['pending','diproses','dikirim','selesai'];
    $curIdx = array_search($p->status, $steps);
?>
<div class="col-12 col-md-6">
    <div class="card h-100" style="border-top:3px solid <?= $sudahBayar ? '#10b981' : '#f59e0b' ?>">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <strong><?= e($p->kode_pesanan) ?></strong>
                    <div class="text-muted" style="font-size:.75rem">
                        <i class="bi bi-clock me-1"></i><?= date('d M Y H:i', strtotime($p->created_at)) ?>
                    </div>
                </div>
                <?= statusBadge($p->status) ?>
            </div>

            <div class="mb-1" style="font-size:.87rem"><i class="bi bi-box-seam me-2 text-muted"></i><?= e($p->daftar_produk ?? '-') ?></div>
            <?php if ($p->catatan && $p->catatan !== '-'): ?>
            <div class="mb-1 text-muted" style="font-size:.82rem"><i class="bi bi-chat me-2"></i><?= e($p->catatan) ?></div>
            <?php endif; ?>

            <hr class="my-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted" style="font-size:.82rem">Total</span>
                <strong class="text-success"><?= formatRupiah($p->total_harga) ?></strong>
            </div>

            <!-- Status Pembayaran -->
            <?php if ($statusBayar === 'lunas'): ?>
                <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#d1fae5;font-size:.82rem">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span style="color:#065f46"><strong>Pembayaran Lunas</strong> — <?= e($p->metode_bayar ?? '') ?></span>
                </div>
            <?php elseif ($statusBayar === 'menunggu_konfirmasi'): ?>
                <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#fef3c7;font-size:.82rem">
                    <i class="bi bi-hourglass-split" style="color:#d97706"></i>
                    <span style="color:#92400e"><strong>Menunggu Konfirmasi</strong> — <?= e($p->metode_bayar ?? '') ?></span>
                </div>
            <?php elseif ($p->status !== 'batal'): ?>
                <a href="/customer/bayar?pesanan_id=<?= $p->id ?>" class="btn btn-success btn-sm w-100 fw-bold">
                    <i class="bi bi-credit-card me-1"></i>Bayar Sekarang — <?= formatRupiah($p->total_harga) ?>
                </a>
            <?php else: ?>
                <div class="text-center text-muted" style="font-size:.82rem">Pesanan dibatalkan</div>
            <?php endif; ?>

            <!-- Progress bar status pesanan -->
            <?php if ($curIdx !== false && $p->status !== 'batal'): ?>
            <div class="mt-3">
                <div class="d-flex">
                    <?php foreach ($steps as $i => $s): ?>
                    <div class="d-flex align-items-center" style="flex:1">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:20px;height:20px;font-size:.6rem;flex-shrink:0;
                                    background:<?= $i<=$curIdx?'#10b981':'#e5e7eb' ?>;
                                    color:<?= $i<=$curIdx?'#fff':'#9ca3af' ?>">
                            <?= $i<$curIdx ? '✓' : ($i===$curIdx ? '●' : $i+1) ?>
                        </div>
                        <?php if ($i < count($steps)-1): ?>
                        <div style="flex:1;height:2px;background:<?= $i<$curIdx?'#10b981':'#e5e7eb' ?>"></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex mt-1">
                    <?php foreach ($steps as $s): ?>
                    <div style="flex:1;font-size:.6rem;text-align:center;color:#6b7db3"><?= ucfirst($s) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
