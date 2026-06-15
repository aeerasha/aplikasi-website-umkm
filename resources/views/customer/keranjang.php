<?php $pageTitle = 'Keranjang Belanja'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-cart3 me-2 text-success"></i>Keranjang Belanja</h4>
        <small class="text-muted">Meja: <strong><?= e($_SESSION['customer']['nomor_meja'] ?? '-') ?></strong> | Nama: <strong><?= e($_SESSION['customer']['nama'] ?? '-') ?></strong></small>
    </div>
</div>

<?php if ($msg = getFlash('success')): ?>
    <div class="alert alert-success border-0 shadow-sm"><?= e($msg) ?></div>
<?php endif; ?>

<?php if (empty($produkData)): ?>
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-cart-x display-1 text-light mb-3"></i>
            <h5 class="text-muted">Keranjang masih kosong</h5>
            <p class="text-muted mb-4">Yuk, tambahkan menu favoritmu sekarang!</p>
            <a href="/customer/katalog" class="btn btn-success px-4">Lihat Katalog Menu</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">Produk</th>
                        <th class="text-center" style="width: 160px;">Jumlah</th>
                        <th class="text-center">Subtotal</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($produkData as $item): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark"><?= e($item->nama) ?></div>
                            <small class="text-muted"><?= formatRupiah($item->harga) ?></small>
                            <input type="text" class="form-control form-control-sm mt-2 border-0 bg-light" 
                                   placeholder="Tambahkan catatan (opsional)..."
                                   style="font-size: 0.85rem;"
                                   value="<?= e($_SESSION['keranjang'][$item->id]['catatan'] ?? '') ?>"
                                   onchange="updateCatatan(<?= $item->id ?>, this.value)">
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <form method="POST" action="/customer/keranjang/tambah">
                                    <input type="hidden" name="produk_id" value="<?= $item->id ?>">
                                    <input type="hidden" name="jumlah" value="<?= $item->jumlah - 1 ?>">
                                    <button class="btn btn-sm btn-light border-0 shadow-sm rounded-circle px-2">-</button>
                                </form>
                                <span class="mx-3 fw-bold" style="min-width: 20px;"><?= $item->jumlah ?></span>
                                <form method="POST" action="/customer/keranjang/tambah">
                                    <input type="hidden" name="produk_id" value="<?= $item->id ?>">
                                    <input type="hidden" name="jumlah" value="<?= $item->jumlah + 1 ?>">
                                    <button class="btn btn-sm btn-light border-0 shadow-sm rounded-circle px-2">+</button>
                                </form>
                            </div>
                        </td>
                        <td class="text-center fw-bold text-success"><?= formatRupiah($item->subtotal) ?></td>
                        <td class="text-center pe-4">
                            <form method="POST" action="/customer/keranjang/hapus">
                                <input type="hidden" name="produk_id" value="<?= $item->id ?>">
                                <button class="btn btn-sm text-danger-emphasis" onclick="return confirm('Hapus item ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="2" class="ps-4 py-3 fw-bold text-end">Total Pembayaran</td>
                        <td class="text-center fw-bold text-success fs-4"><?= formatRupiah($total) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="card-footer bg-white p-4 border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <form method="POST" action="/customer/keranjang/kosongkan" onsubmit="return confirm('Kosongkan keranjang?')">
                    <button class="btn btn-link text-decoration-none text-muted p-0">
                        <i class="bi bi-trash3 me-1"></i>Hapus Semua
                    </button>
                </form>
                
                <div class="vr"></div> <a href="/customer/katalog" class="text-decoration-none text-success fw-bold">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </a>
            </div>
            <a href="/customer/checkout" class="btn btn-success fw-bold px-5 py-2 shadow-sm rounded-pill">
                <i class="bi bi-bag-check me-2"></i>Checkout Sekarang
            </a>
        </div>
    </div>
<?php endif; ?>

<script>
function updateCatatan(id, val) {
    fetch('/customer/keranjang/update-catatan', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'produk_id=' + id + '&catatan=' + encodeURIComponent(val)
    });
}
</script>