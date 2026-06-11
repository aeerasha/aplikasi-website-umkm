<?php $pageTitle = 'Edit Pembayaran'; ?>
<div class="page-header">
    <a href="/pembayaran" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-pencil-square me-2" style="color:var(--accent)"></i>Edit Pembayaran</h4>
</div>
<div class="row"><div class="col-md-8 col-lg-7">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/pembayaran/update">
            <input type="hidden" name="id" value="<?= $pembayaran->id ?>">
            <div class="mb-3">
                <label class="form-label fw-semibold">Pesanan</label>
                <select name="pesanan_id" class="form-select">
                    <option value="">-- Pilih Pesanan --</option>
                    <?php foreach ($pesanans as $ps): ?>
                    <option value="<?= $ps->id ?>" <?= $pembayaran->pesanan_id == $ps->id ? 'selected' : '' ?>>
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
                        <option value="<?= $m ?>" <?= $pembayaran->metode === $m ? 'selected' : '' ?>><?= $m ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" value="<?= $pembayaran->jumlah ?>" min="0">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['pending','lunas','gagal'] as $s): ?>
                    <option value="<?= $s ?>" <?= $pembayaran->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"><?= e($pembayaran->catatan) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
                <a href="/pembayaran" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
