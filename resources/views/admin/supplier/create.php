<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Data Supplier</h4>
    <a href="/supplier" class="btn btn-outline-secondary btn-sm rounded-9">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body p-4">
        <form action="/supplier" method="POST">
            <div class="mb-3">
                <label for="nama_supplier" class="form-label fw-semibold">Nama Perusahaan / Supplier</label>
                <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" required placeholder="Contoh: PT. Sumber Rasa Nusantara">
            </div>

            <div class="mb-3">
                <label for="nama_kontak" class="form-label fw-semibold">Nama PIC / Kontak</label>
                <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" required placeholder="Contoh: Budi Santoso">
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" 
                    pattern="[0-9]+" 
                    title="Hanya boleh memasukkan angka" 
                    value="<?= $supplier->telepon ?? '' ?>" 
                    required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label fw-semibold">Alamat Kantor/Gudang</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required placeholder="Tulis alamat lengkap..."></textarea>
            </div>

            <div class="mb-4">
                <label for="keterangan" class="form-label fw-semibold">Keterangan Tambahan (Opsional)</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Supplier khusus biji kopi dan sirup">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-save me-2"></i> Daftarkan Supplier
            </button>
        </form>
    </div>
</div>