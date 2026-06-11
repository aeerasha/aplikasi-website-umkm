<?php $pageTitle = 'Profil Saya'; ?>
<div class="mb-4">
    <h4 class="fw-800 mb-0" style="color:#1a1f36"><i class="bi bi-person-circle me-2" style="color:#10b981"></i>Profil Saya</h4>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width:70px;height:70px;font-size:1.6rem;background:linear-gradient(135deg,#10b981,#059669)">
                <?= strtoupper(substr($user->nama??'?',0,1)) ?>
            </div>
            <h5 class="fw-bold mb-1"><?= e($user->nama??'') ?></h5>
            <span class="badge" style="background:#d1fae5;color:#065f46">Pelanggan</span>
            <div class="text-muted mt-2" style="font-size:.8rem"><i class="bi bi-calendar3 me-1"></i>Bergabung <?= date('M Y', strtotime($user->created_at??'now')) ?></div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Profil</div>
            <div class="card-body p-4">
                <form method="POST" action="/customer/profile">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= e($user->nama??'') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="<?= e($user->email??'') ?>" disabled>
                        <small class="text-muted">Email tidak dapat diubah</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?= e($user->telepon??'') ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= e($user->alamat??'') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
