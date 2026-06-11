<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Alat</h4>
    <a href="/inventaris" class="btn btn-outline-secondary btn-sm rounded-9">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body p-4">
        <form action="/inventaris/update" method="POST">
            <input type="hidden" name="id" value="<?= $alat->id ?>">

            <div class="mb-3">
                <label for="nama_alat" class="form-label fw-semibold">Nama Alat / Perlengkapan</label>
                <input type="text" class="form-control" id="nama_alat" name="nama_alat" value="<?= e($alat->nama_alat) ?>" required>
            </div>

            <div class="mb-3">
                <label for="kategori" class="form-label fw-semibold">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" value="<?= e($alat->kategori) ?>" required>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label fw-semibold">Jumlah (Unit)</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= e($alat->jumlah) ?>" min="0" required>
            </div>

            <div class="mb-4">
                <label for="kondisi" class="form-label fw-semibold">Kondisi Alat</label>
                <select class="form-select" id="kondisi" name="kondisi" required>
                    <option value="Baik" <?= $alat->kondisi === 'Baik' ? 'selected' : '' ?>>Baik</option>
                    <option value="Perawatan" <?= $alat->kondisi === 'Perawatan' ? 'selected' : '' ?>>Perawatan / Maintenance</option>
                    <option value="Rusak" <?= $alat->kondisi === 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-check-circle me-2"></i> Perbarui Data
            </button>
        </form>
    </div>
</div>