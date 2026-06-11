<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Supplier</h4>
    <a href="/supplier" class="btn btn-outline-secondary btn-sm rounded-9">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body p-4">
        <form action="/supplier/update" method="POST">
            <input type="hidden" name="id" value="<?= $supplier->id ?>">

            <div class="mb-3">
                <label for="nama_supplier" class="form-label fw-semibold">Nama Perusahaan / Supplier</label>
                <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="<?= e($supplier->nama_supplier) ?>" required>
            </div>

            <div class="mb-3">
                <label id="nama_kontak" class="form-label fw-semibold">Nama PIC / Kontak</label>
                <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" value="<?= e($supplier->nama_kontak) ?>" required>
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
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= e($supplier->alamat) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="keterangan" class="form-label fw-semibold">Keterangan Tambahan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= e($supplier->keterangan) ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>