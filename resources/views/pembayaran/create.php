<?php $pageTitle = 'Catat Pembayaran'; ?>
<div class="page-header">
    <a href="/pembayaran" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-plus-circle me-2" style="color:var(--accent)"></i>Catat Pembayaran Baru</h4>
</div>
<div class="row"><div class="col-md-8 col-lg-7">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/pembayaran">
            <div class="mb-3">
                <label class="form-label fw-semibold">Pesanan</label>
                <select name="pesanan_id" class="form-select" onchange="fillHarga(this)">
                    <option value="">-- Pilih Pesanan --</option>
                    <?php foreach ($pesanans as $ps): ?>
                    <option value="<?= $ps->id ?>" data-harga="<?= $ps->total_harga ?>">
                        <?= e($ps->kode_pesanan) ?> — <?= e($ps->nama_pelanggan) ?> (<?= formatRupiah($ps->total_harga) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Metode Pembayaran</label>
                    <select name="metode" class="form-select">
                        <?php foreach (['Tunai','Transfer Bank','QRIS','Debit','Kredit','E-Wallet'] as $m): ?>
                        <option value="<?= $m ?>"><?= $m ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control" id="jumlahInput" placeholder="0" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['pending','lunas','gagal'] as $s): ?>
                    <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Keterangan tambahan..."></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                <a href="/pembayaran" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
<script>
function fillHarga(sel) {
    const harga = sel.options[sel.selectedIndex]?.dataset?.harga || '';
    document.getElementById('jumlahInput').value = harga;
}
</script>
