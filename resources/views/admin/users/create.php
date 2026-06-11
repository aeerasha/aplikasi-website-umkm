<?php $pageTitle = 'Tambah Akun'; ?>
<div class="page-header">
    <a href="/admin/users" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-person-plus me-2" style="color:var(--accent)"></i>Tambah Akun Pengguna</h4>
</div>
<div class="row"><div class="col-md-7 col-lg-6">
<div class="card"><div class="card-body p-4">
    <form method="POST" action="/admin/users">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                <option value="pelanggan">Pelanggan</option>
                <option value="pegawai">Pegawai</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Telepon</label>
            <input type="text" name="telepon" class="form-control">
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2"></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            <a href="/admin/users" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
</div></div>
