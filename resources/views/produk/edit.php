<?php $pageTitle = 'Edit Produk'; ?>
<div class="page-header mb-4">
    <a href="/produk" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-pencil-square me-2" style="color:var(--accent)"></i>Edit Produk</h4>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="/produk/update" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?= $produk->id ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Produk (Biarkan kosong jika tidak ingin diubah)</label>
                        <input type="file" name="gambar" id="gambarInput" class="form-control" accept="image/*" onchange="validateFileSize(this)">
                        <div id="fileError" class="text-danger mt-1 fw-bold" style="display:none; font-size: 0.875em;">Ukuran file maksimal 2 MB!</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="namaProduk" class="form-control" 
                               value="<?= e($produk->nama) ?>" maxlength="100" required oninput="handleValidation(this)">
                        <div id="namaCharError" class="text-danger mt-1 fw-bold" style="display:none; font-size: 0.875em;">Maksimal 100 karakter!</div>
                        <div id="namaRequiredError" class="invalid-feedback">Nama produk wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategoris as $k): ?>
                                <option value="<?= $k->id ?>" <?= $produk->kategori_id == $k->id ? 'selected' : '' ?>>
                                    <?= e($k->nama) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" value="<?= (int)$produk->harga ?>" min="0" step="1" required>
                            <small class="text-muted">Masukkan 0 jika gratis.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" value="<?= (int)$produk->stok ?>" min="0" step="1" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= e($produk->deskripsi) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Panggil fungsi validasi saat halaman loading agar kondisi data lama terdeteksi
window.onload = function() {
    handleValidation(document.getElementById('namaProduk'));
};

function handleValidation(input) {
    var charError = document.getElementById('namaCharError');
    var requiredError = document.getElementById('namaRequiredError');
    if (input.value.length >= 100) {
        charError.style.display = 'block';
        requiredError.style.display = 'none';
        input.classList.add('is-invalid');
    } else {
        charError.style.display = 'none';
        requiredError.style.display = (input.value.length === 0) ? 'block' : 'none';
        input.classList.remove('is-invalid');
    }
}

function validateFileSize(input) {
    const file = input.files[0];
    const fileError = document.getElementById('fileError');
    if (file && file.size > 2 * 1024 * 1024) {
        fileError.style.display = 'block';
        input.value = '';
    } else {
        fileError.style.display = 'none';
    }
}
</script>