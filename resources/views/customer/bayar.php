<?php $pageTitle = 'Bayar Pesanan'; ?>

<div class="mb-4">
    <a href="/customer/pesanan-saya" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Pesanan
    </a>
    <h4 class="fw-800 mb-0" style="color:#1a1f36">
        <i class="bi bi-credit-card me-2" style="color:#10b981"></i>Pembayaran Pesanan
    </h4>
</div>

<div class="row g-4">
    <!-- Kiri: Detail Pesanan -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header fw-bold">
                <i class="bi bi-receipt me-2 text-success"></i>Detail Pesanan
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Kode Pesanan</span>
                    <strong><?= e($pesanan->kode_pesanan) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Produk</span>
                    <span><?= e($pesanan->nama_produk ?? '-') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Jumlah</span>
                    <span><?= $pesanan->jumlah ?> pcs</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    <span><?= statusBadge($pesanan->status) ?></span>
                </div>
                <?php if ($pesanan->catatan && $pesanan->catatan !== '-'): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Catatan</span>
                    <span class="text-end" style="max-width:160px"><?= e($pesanan->catatan) ?></span>
                </div>
                <?php endif; ?>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total Tagihan</span>
                    <span class="fw-800 fs-5 text-success"><?= formatRupiah($pesanan->total_harga) ?></span>
                </div>
            </div>
        </div>

        <!-- Info Rekening -->
        <div class="card mt-3" style="border:2px dashed #d1fae5">
            <div class="card-body p-3">
                <div class="fw-bold mb-2" style="color:#065f46"><i class="bi bi-info-circle me-1"></i>Info Transfer</div>
                <div style="font-size:.85rem">
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">BCA</span><strong>1234-5678-9012</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Mandiri</span><strong>0987-6543-2100</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">QRIS</span><strong>Scan kode di kasir</strong></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">A/N</span><strong>Shahira Pemilik</strong></div>
                </div>
                <div class="mt-2 p-2 rounded" style="background:#d1fae5;font-size:.78rem;color:#065f46">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    Mohon upload bukti transfer setelah melakukan pembayaran.
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Form Bayar -->
    <div class="col-md-7">
        <?php if ($bayarAda && $bayarAda->status === 'menunggu_konfirmasi'): ?>
        <!-- Status menunggu konfirmasi -->
        <div class="card" style="border:2px solid #fde68a">
            <div class="card-body text-center p-5">
                <div style="font-size:3rem">⏳</div>
                <h5 class="fw-bold mt-3 mb-1">Menunggu Konfirmasi Admin</h5>
                <p class="text-muted mb-3">Pembayaran Anda sedang diverifikasi oleh admin. Biasanya selesai dalam 1×24 jam.</p>
                <div class="p-3 rounded-3 text-start mb-4" style="background:#fffbeb;border:1px solid #fde68a;font-size:.85rem">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Metode</span>
                        <strong><?= e($bayarAda->metode) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Jumlah</span>
                        <strong class="text-success"><?= formatRupiah($bayarAda->jumlah) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Dikirim</span>
                        <span><?= date('d M Y H:i', strtotime($bayarAda->created_at)) ?></span>
                    </div>
                    <?php if ($bayarAda->bukti_transfer): ?>
                    <div class="mt-2 pt-2 border-top">
                        <span class="text-muted">Bukti:</span>
                        <a href="/uploads/bukti/<?= e($bayarAda->bukti_transfer) ?>" target="_blank" class="ms-2 text-success">
                            <i class="bi bi-paperclip me-1"></i>Lihat Bukti
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <a href="/customer/pesanan-saya" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Pesanan
                </a>
            </div>
        </div>

        <?php else: ?>
        <!-- Form Pembayaran -->
        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-wallet2 me-2 text-success"></i>Pilih Metode Pembayaran
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/customer/bayar" enctype="multipart/form-data">
                    <input type="hidden" name="pesanan_id" value="<?= $pesanan->id ?>">

                    <!-- Pilih metode -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <?php
                            $metodes = [
                                ['val'=>'Tunai',         'icon'=>'💵', 'label'=>'Tunai',          'sub'=>'Bayar langsung di tempat'],
                                ['val'=>'Transfer Bank', 'icon'=>'🏦', 'label'=>'Transfer Bank',   'sub'=>'BCA / Mandiri / BRI'],
                                ['val'=>'QRIS',          'icon'=>'📱', 'label'=>'QRIS',            'sub'=>'Gopay / OVO / Dana'],
                                ['val'=>'E-Wallet',      'icon'=>'👛', 'label'=>'E-Wallet',        'sub'=>'ShopeePay / LinkAja'],
                            ];
                            ?>
                            <?php foreach ($metodes as $m): ?>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="metode" id="m_<?= $m['val'] ?>" value="<?= $m['val'] ?>" required>
                                <label class="btn btn-outline-secondary w-100 text-start p-3" for="m_<?= $m['val'] ?>" style="border-radius:12px;border:2px solid #e2e8f0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="font-size:1.4rem"><?= $m['icon'] ?></span>
                                        <div>
                                            <div class="fw-bold" style="font-size:.88rem"><?= $m['label'] ?></div>
                                            <div class="text-muted" style="font-size:.73rem"><?= $m['sub'] ?></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Upload bukti -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Bukti Pembayaran
                            <span class="text-muted fw-normal" style="font-size:.8rem">(opsional, jpg/png/pdf maks 2MB)</span>
                        </label>
                        <div class="upload-area rounded-3 p-4 text-center" style="border:2px dashed #d1fae5;background:#f0fdf4;cursor:pointer" onclick="document.getElementById('buktiInput').click()">
                            <i class="bi bi-cloud-upload fs-3 text-success"></i>
                            <div class="mt-1 fw-semibold" style="color:#065f46">Klik untuk upload bukti</div>
                            <div class="text-muted" style="font-size:.78rem">atau drag & drop file di sini</div>
                            <div id="namaFile" class="mt-2 text-success fw-semibold" style="font-size:.82rem"></div>
                        </div>
                        <input type="file" id="buktiInput" name="bukti_transfer" class="d-none" accept=".jpg,.jpeg,.png,.pdf"
                               onchange="document.getElementById('namaFile').textContent = this.files[0]?.name ?? ''">
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Catatan Pembayaran</label>
                        <textarea name="catatan" class="form-control" rows="2"
                                  placeholder="Contoh: Transfer dari BCA atas nama Andi..."></textarea>
                    </div>

                    <!-- Total + tombol -->
                    <div class="p-3 rounded-3 mb-4 d-flex justify-content-between align-items-center" style="background:#d1fae5">
                        <span class="fw-bold" style="color:#065f46">Total yang dibayarkan</span>
                        <span class="fw-800 fs-5 text-success"><?= formatRupiah($pesanan->total_harga) ?></span>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-6">
                        <i class="bi bi-send-check me-2"></i>Kirim Pembayaran
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.btn-check:checked + .btn-outline-secondary {
    background: #d1fae5 !important;
    border-color: #10b981 !important;
    color: #065f46 !important;
}
</style>
