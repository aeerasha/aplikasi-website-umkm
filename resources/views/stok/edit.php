<?php $pageTitle = 'Edit Stok'; ?>
<div class="page-header">
    <a href="/stok" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-pencil-square me-2" style="color:var(--accent)"></i>Edit Stok Bahan Baku</h4>
</div>
<div class="row"><div class="col-md-8 col-lg-7">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/stok/update">
            <input type="hidden" name="id" value="<?= $stok->id ?>">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-semibold">Nama Bahan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_bahan" class="form-control" value="<?= e($stok->nama_bahan) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Satuan</label>
                    <select name="satuan" class="form-select">
                        <?php foreach (['Kg','Gram','Liter','mL','Butir','Pcs','Bungkus','Karton'] as $s): ?>
                        <option value="<?= $s ?>" <?= $stok->satuan === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Jumlah Stok</label>
                    <input type="number" name="jumlah" class="form-control" value="<?= $stok->jumlah ?>" min="0" step="0.01">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Stok Minimum</label>
                    <input type="number" name="stok_minimum" class="form-control" value="<?= $stok->stok_minimum ?>" min="0" step="0.01">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Harga/Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" class="form-control" value="<?= $stok->harga_satuan ?>">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Supplier</label>
                <input type="text" name="supplier" class="form-control" value="<?= e($stok->supplier) ?>">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
                <a href="/stok" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
