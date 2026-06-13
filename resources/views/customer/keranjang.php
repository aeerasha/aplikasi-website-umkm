<?php $pageTitle = 'Keranjang Belanja'; ?>
<div class="page-header mb-4">
    <h4><i class="bi bi-cart3 me-2" style="color:var(--accent)"></i>Keranjang Belanja</h4>
    <small class="text-muted">Meja <?= e($_SESSION['customer']['nomor_meja'] ?? '-') ?> — <?= e($_SESSION['customer']['nama'] ?? '') ?></small>
</div>

<?php if ($msg = getFlash('success')): ?>
    <div class="alert alert-success border-0"><?= e($msg) ?></div>
<?php endif; ?>
<?php if ($msg = getFlash('error')): ?>
    <div class="alert alert-danger border-0"><?= e($msg) ?></div>
<?php endif; ?>

<?php if (empty($produkData)): ?>
<div class="card text-center py-5">
    <div class="card-body">
        <i class="bi bi-cart-x display-4 text-muted mb-3 d-block"></i>
        <p class="text-muted">Keranjang masih kosong.</p>
        <a href="/customer/katalog" class="btn btn-success">Lihat Menu</a>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Produk</th>
                    <th class="text-center">Harga Satuan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Subtotal</th>
                    <th class="text-center pe-3">Hapus</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($produkData as $item): ?>
            <tr>
                <td class="ps-3">
                    <?php if ($item->gambar): ?>
                        <img src="/uploads/produk/<?= e($item->gambar) ?>" width="40" height="40"
                             class="rounded me-2" style="object-fit:cover">
                    <?php endif; ?>
                    <strong><?= e($item->nama) ?></strong>
                </td>
                <td class="text-center"><?= formatRupiah($item->harga) ?></td>
                <td class="text-center"><?= $item->jumlah ?></td>
                <td class="text-center fw-bold text-success"><?= formatRupiah($item->subtotal) ?></td>
                <td class="text-center pe-3">
                    <form method="POST" action="/customer/keranjang/hapus" class="d-inline">
                        <input type="hidden" name="produk_id" value="<?= $item->id ?>">
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus item ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="3" class="ps-3 fw-bold">Total</td>
                    <td class="text-center fw-bold text-success fs-5"><?= formatRupiah($total) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <form method="POST" action="/customer/keranjang/kosongkan"
              onsubmit="return confirm('Kosongkan seluruh keranjang?')">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-trash me-1"></i>Kosongkan
            </button>
        </form>
        <form method="POST" action="/customer/buat-pesanan">
            <button class="btn btn-success fw-bold px-4">
                <i class="bi bi-bag-check me-2"></i>Checkout Sekarang
            </button>
        </form>
    </div>
</div>
<?php endif; ?>
