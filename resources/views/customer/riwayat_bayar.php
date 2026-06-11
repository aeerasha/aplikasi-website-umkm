<?php $pageTitle = 'Riwayat Pembayaran'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-800 mb-0" style="color:#1a1f36">
            <i class="bi bi-clock-history me-2" style="color:#10b981"></i>Riwayat Pembayaran
        </h4>
        <small class="text-muted">Semua transaksi pembayaran Anda</small>
    </div>
</div>

<?php if (empty($riwayat)): ?>
<div class="card text-center p-5">
    <i class="bi bi-credit-card fs-1 text-muted mb-3"></i>
    <h5 class="text-muted">Belum ada riwayat pembayaran</h5>
    <a href="/customer/katalog" class="btn btn-success mx-auto mt-2" style="width:fit-content">
        <i class="bi bi-grid me-1"></i>Mulai Belanja
    </a>
</div>
<?php else: ?>
<div class="row g-3">
<?php foreach ($riwayat as $r):
    $statusColor = match($r->status) {
        'lunas'               => ['bg'=>'#d1fae5','text'=>'#065f46','icon'=>'bi-check-circle-fill'],
        'menunggu_konfirmasi' => ['bg'=>'#fef3c7','text'=>'#92400e','icon'=>'bi-hourglass-split'],
        'pending'             => ['bg'=>'#e0e7ff','text'=>'#3730a3','icon'=>'bi-clock'],
        default               => ['bg'=>'#fee2e2','text'=>'#991b1b','icon'=>'bi-x-circle-fill'],
    };
    $statusLabel = match($r->status) {
        'lunas'               => 'Lunas',
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'pending'             => 'Belum Dibayar',
        default               => ucfirst($r->status),
    };
?>
<div class="col-12 col-md-6">
    <div class="card h-100" style="border-left:4px solid <?= $r->status==='lunas' ? '#10b981' : ($r->status==='menunggu_konfirmasi' ? '#f59e0b' : '#6366f1') ?>">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <strong><?= e($r->kode_pesanan) ?></strong>
                    <div class="text-muted" style="font-size:.78rem"><?= date('d M Y H:i', strtotime($r->created_at)) ?></div>
                </div>
                <span class="badge d-flex align-items-center gap-1" style="background:<?= $statusColor['bg'] ?>;color:<?= $statusColor['text'] ?>">
                    <i class="bi <?= $statusColor['icon'] ?>"></i><?= $statusLabel ?>
                </span>
            </div>
            <div class="mb-1" style="font-size:.87rem">
                <i class="bi bi-box-seam me-2 text-muted"></i><?= e($r->nama_produk ?? '-') ?>
            </div>
            <div class="mb-1" style="font-size:.87rem">
                <i class="bi bi-wallet2 me-2 text-muted"></i><?= e($r->metode ?? '-') ?>
            </div>
            <?php if ($r->catatan): ?>
            <div class="mb-1 text-muted" style="font-size:.82rem">
                <i class="bi bi-chat me-2"></i><?= e($r->catatan) ?>
            </div>
            <?php endif; ?>
            <?php if ($r->bukti_transfer): ?>
            <div class="mb-1" style="font-size:.82rem">
                <i class="bi bi-paperclip me-2 text-muted"></i>
                <a href="/uploads/bukti/<?= e($r->bukti_transfer) ?>" target="_blank" class="text-success">Lihat Bukti Transfer</a>
            </div>
            <?php endif; ?>
            <hr class="my-2">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size:.82rem">Total Dibayar</span>
                <strong class="text-success"><?= formatRupiah($r->jumlah) ?></strong>
            </div>
            <?php if ($r->status === 'pending' || $r->status === 'menunggu_konfirmasi'): ?>
            <?php if ($r->status === 'pending'): ?>
            <a href="/customer/bayar?pesanan_id=<?= $r->pesanan_id ?>" class="btn btn-success btn-sm w-100 mt-2">
                <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
