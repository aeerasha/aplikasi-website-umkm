<?php $pageTitle = 'Tambah Pegawai'; ?>
<div class="page-header">
    <a href="/pegawai" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-person-plus me-2" style="color:var(--accent)"></i>Tambah Pegawai</h4>
    <small>Isi form untuk mendaftarkan pegawai baru</small>
</div>
<div class="row"><div class="col-md-8 col-lg-7">
<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="/pegawai">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama pegawai" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jabatan</label>
                    <select name="jabatan" class="form-select">
                        <option value="">-- Pilih Jabatan --</option>
                        <?php foreach (['Manajer','Kasir','Koki','Pelayan','Pengiriman','Admin','Lainnya'] as $j): ?>
                        <option value="<?= $j ?>"><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control" 
                        pattern="[0-9]+" 
                        title="Hanya boleh memasukkan angka" 
                        value="<?= e($pegawai->telepon ?? '') ?>" 
                        required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Gaji (Rp)</label>
                <input type="number" name="gaji" class="form-control" placeholder="2500000" min="0">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                <a href="/pegawai" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
