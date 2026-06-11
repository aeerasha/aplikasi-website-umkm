<?php $pageTitle = 'Buat Pesanan'; ?>
<div class="mb-4">
    <a href="/customer/katalog" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog</a>
    <h4 class="fw-800 mb-0" style="color:#1a1f36"><i class="bi bi-cart-plus me-2" style="color:#10b981"></i>Buat Pesanan Baru</h4>
</div>
<div class="row"><div class="col-md-7 col-lg-6">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/customer/buat-pesanan">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" placeholder="08xx" value="<?= e(auth()['telepon'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                <select name="produk_id" class="form-select" id="produkSel" onchange="hitungTotal()" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($produks as $p): ?>
                    <option value="<?= $p->id ?>" data-harga="<?= $p->harga ?>" data-stok="<?= $p->stok ?>" <?= $produk_id==$p->id?'selected':'' ?>>
                        <?= e($p->nama) ?> — <?= formatRupiah($p->harga) ?> (Stok: <?= $p->stok ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number" name="jumlah" id="jumlahInput" class="form-control" value="1" min="1" onchange="hitungTotal()">
                <div id="stokInfo" class="form-text text-muted"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Estimasi Total</label>
                <div class="p-3 rounded-3 text-center fw-bold fs-5 text-success" style="background:#d1fae5" id="totalDisplay">Rp 0</div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Tidak pedas, extra saos..."></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-bold"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pesanan</button>
        </form>
    </div>
</div>
</div></div>
<script>
function hitungTotal() {
    const sel = document.getElementById('produkSel');
    const opt = sel.options[sel.selectedIndex];
    const harga = parseFloat(opt?.dataset?.harga || 0);
    const stok  = parseInt(opt?.dataset?.stok || 0);
    const jml   = parseInt(document.getElementById('jumlahInput').value || 1);
    document.getElementById('totalDisplay').textContent = 'Rp ' + (harga * jml).toLocaleString('id-ID');
    document.getElementById('stokInfo').textContent = stok ? `Stok tersedia: ${stok}` : '';
    document.getElementById('jumlahInput').max = stok || 999;
}
document.addEventListener('DOMContentLoaded', hitungTotal);
</script>
