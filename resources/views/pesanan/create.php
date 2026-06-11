<?php $pageTitle = 'Buat Pesanan'; ?>
<div class="page-header">
    <a href="/pesanan" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-plus-circle me-2" style="color:var(--accent)"></i>Buat Pesanan Baru</h4>
</div>
<div class="row"><div class="col-md-8 col-lg-7">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/pesanan">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama lengkap pelanggan" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control" placeholder="08xx">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Produk</label>
                <select name="produk_id" class="form-select" id="produkSelect" onchange="updateHarga()">
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($produks as $p): ?>
                    <option value="<?= $p->id ?>" data-harga="<?= $p->harga ?>"><?= e($p->nama) ?> (<?= formatRupiah($p->harga) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" value="1" min="1" id="jumlahInput" onchange="updateHarga()">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['pending','diproses','dikirim','selesai','batal'] as $s): ?>
                        <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Estimasi Total</label>
                    <div class="form-control bg-light fw-bold text-success" id="totalDisplay">Rp 0</div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Buat Pesanan</button>
                <a href="/pesanan" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
<script>
function updateHarga() {
    const sel = document.getElementById('produkSelect');
    const harga = sel.options[sel.selectedIndex]?.dataset?.harga || 0;
    const jumlah = document.getElementById('jumlahInput').value || 1;
    const total = harga * jumlah;
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
