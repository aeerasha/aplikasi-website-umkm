<?php $pageTitle = 'Tambah Produk'; ?>
<div class="page-header mb-4">
    <a href="/produk" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-plus-circle me-2" style="color:var(--accent)"></i>Tambah Produk Baru</h4>
</div>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger mb-4"><?= $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="/produk" enctype="multipart/form-data" class="needs-validation" novalidate>
                    
                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Produk</label>
                    <input type="file" name="gambar" id="gambarInput" class="form-control" accept="image/*" onchange="validateFileSize(this)">
                    
                    <div id="fileError" class="text-danger mt-1 fw-bold" style="display:none; font-size: 0.875em;">
                        Ukuran file maksimal 2 MB!
                    </div>
                </div>

                <script>
                function validateFileSize(input) {
                    const file = input.files[0];
                    const fileError = document.getElementById('fileError');
                    const maxSize = 2 * 1024 * 1024; // 2 MB dalam bytes

                    if (file && file.size > maxSize) {
                        fileError.style.display = 'block';
                        input.classList.add('is-invalid');
                        input.value = ''; // Hapus file yang dipilih agar tidak terunggah
                    } else {
                        fileError.style.display = 'none';
                        input.classList.remove('is-invalid');
                    }
                }
                </script>  
                
                <div class="mb-3">
    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
    
    <input type="text" name="nama" id="namaProduk" class="form-control" 
           placeholder="Masukkan nama produk" maxlength="100" required 
           oninput="handleValidation(this)">
    
    <div id="namaCharError" class="text-danger mt-1 fw-bold" style="display:none; font-size: 0.875em;">
        Maksimal 100 karakter!
    </div>
    
    <div id="namaRequiredError" class="invalid-feedback">
        Nama produk wajib diisi.
    </div>
</div>

<script>
function handleValidation(input) {
    var charError = document.getElementById('namaCharError');
    var requiredError = document.getElementById('namaRequiredError');

    // Cek jika sudah mencapai batas 100
    if (input.value.length >= 100) {
        charError.style.display = 'block';
        requiredError.style.display = 'none'; // Sembunyikan pesan wajib diisi
        input.classList.add('is-invalid');
    } else {
        charError.style.display = 'none';
        
        // Cek jika input kosong untuk menampilkan pesan wajib diisi
        if (input.value.length === 0) {
            requiredError.style.display = 'block';
        } else {
            requiredError.style.display = 'none';
            input.classList.remove('is-invalid');
        }
    }
}
</script>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategoris as $k): ?>
                                <option value="<?= $k->id ?>"><?= e($k->nama) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Kategori wajib dipilih.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" min="0" step="1" required>
                            <small class="text-muted">Masukkan 0 jika produk gratis.</small>
                            <div class="invalid-feedback">Harga wajib diisi.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" min="0" required>
                            
                            <div class="invalid-feedback">
                                Stok tidak boleh negatif!
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Produk</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>