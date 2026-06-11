<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Inventaris Alat</h4>
    <a href="/inventaris" class="btn btn-outline-secondary btn-sm rounded-9">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm col-md-8 mx-auto">
    <div class="card-body p-4">
        <form action="/inventaris" method="POST">
            <div class="mb-3">
                <label for="nama_alat" class="form-label fw-semibold">Nama Alat / Perlengkapan</label>
                <input type="text" class="form-control" id="nama_alat" name="nama_alat" required placeholder="Contoh: Blender Heavy Duty, Wajan Stainless">
            </div>

            <div class="mb-3">
                <label for="kategori" class="form-label fw-semibold">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" required placeholder="Contoh: Alat Dapur, Elektronik, Operasional">
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label fw-semibold">Jumlah (Unit)</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required placeholder="0">
            </div>

            <div class="mb-4">
                <label for="kondisi" class="form-label fw-semibold">Kondisi Awal</label>
                <select class="form-select" id="kondisi" name="kondisi" required>
                    <option value="Baik">Baik</option>
                    <option value="Perawatan">Perawatan / Maintenance</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-save me-2"></i> Simpan Alat Baru
            </button>
        </form>
    </div>
</div>