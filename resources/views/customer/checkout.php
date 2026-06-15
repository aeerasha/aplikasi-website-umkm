<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;"> <div class="card-body p-4">
            
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="text-uppercase text-secondary fw-bold mb-3" style="letter-spacing: 1px; font-size: 0.75rem;">Informasi Pelanggan</h6>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Nama:</span>
                    <span class="fw-bold text-dark"><?= e($_SESSION['customer']['nama']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">No. HP:</span>
                    <span class="fw-bold text-dark"><?= e($_SESSION['customer']['no_hp']) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">No. Meja:</span>
                    <span class="fw-bold text-primary"><i class="bi bi-geo-alt-fill"></i> <?= e($_SESSION['customer']['nomor_meja']) ?></span>
                </div>
            </div>

            <table class="table table-borderless align-middle mb-4">
                <thead>
                    <tr class="text-secondary" style="font-size: 0.75rem;">
                        <th>PRODUK</th>
                        <th class="text-center">QTY</th>
                        <th class="text-end">HARGA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produkData as $p): ?>
                    <tr class="border-bottom border-light">
                        <td class="py-2">
                            <div class="fw-bold text-dark small"><?= e($p->nama) ?></div>
                        </td>
                        <td class="text-center text-muted small"><?= $p->jumlah ?></td>
                        <td class="text-end fw-semibold small">Rp <?= number_format($p->subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end pt-3 fw-bold">TOTAL</td>
                        <td class="text-end pt-3 text-success fw-bold">Rp <?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="d-flex gap-2 mt-4">
                <a href="/customer/keranjang" class="btn btn-light w-50 py-2 shadow-sm border">Kembali</a>
                <form action="/customer/qris" method="GET" class="w-50">
                    <button type="submit" class="btn btn-success w-100 fw-bold">
                        <i class="bi bi-check-circle me-1"></i> Konfirmasi Pesanan
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</div>