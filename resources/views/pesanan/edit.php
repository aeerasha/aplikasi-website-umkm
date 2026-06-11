<?php $pageTitle = 'Edit Pesanan'; ?>
<div class="page-header">
    <a href="/pesanan" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-pencil-square me-2" style="color:var(--accent)"></i>Edit Pesanan — <?= e($pesanan->kode_pesanan) ?></h4>
</div>
<div class="row"><div class="col-md-8 col-lg-7">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/pesanan/update">
            <input type="hidden" name="id" value="<?= $pesanan->id ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pelanggan" class="form-control" value="<?= e($pesanan->nama_pelanggan) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="<?= e($pesanan->telepon) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Produk</label>
                <select name="produk_id" class="form-select">
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($produks as $p): ?>
                    <option value="<?= $p->id ?>" <?= $pesanan->produk_id == $p->id ? 'selected' : '' ?>><?= e($p->nama) ?> (<?= formatRupiah($p->harga) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" value="<?= $pesanan->jumlah ?>" min="1">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['pending','diproses','dikirim','selesai','batal'] as $s): ?>
                        <option value="<?= $s ?>" <?= $pesanan->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Total Saat Ini</label>
                    <div class="form-control bg-light fw-bold text-success"><?= formatRupiah($pesanan->total_harga) ?></div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"><?= e($pesanan->catatan) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
                <a href="/pesanan" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
