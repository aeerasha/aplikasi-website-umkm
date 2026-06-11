<?php $pageTitle = 'Edit Akun'; ?>
<div class="page-header">
    <a href="/admin/users" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4><i class="bi bi-pencil-square me-2" style="color:var(--accent)"></i>Edit Akun: <?= e($user->nama) ?></h4>
</div>
<div class="row"><div class="col-md-7 col-lg-6">
<div class="card"><div class="card-body p-4">
    <form method="POST" action="/admin/users/update">
        <input type="hidden" name="id" value="<?= $user->id ?>">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= e($user->nama) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($user->email) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak ubah)</small></label>
            <input type="password" name="password" class="form-control" placeholder="••••••••">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select">
                    <?php foreach (['pelanggan','pegawai','admin'] as $r): ?>
                    <option value="<?= $r ?>" <?= $user->role===$r?'selected':'' ?>><?= ucfirst($r) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="aktif" class="form-select">
                    <option value="1" <?= $user->aktif?'selected':'' ?>>Aktif</option>
                    <option value="0" <?= !$user->aktif?'selected':'' ?>>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="<?= e($user->telepon) ?>">
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2"><?= e($user->alamat) ?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Perbarui</button>
            <a href="/admin/users" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
</div></div>
