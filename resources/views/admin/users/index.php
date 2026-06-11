<?php $pageTitle = 'Manajemen Akun'; ?>
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div><h4><i class="bi bi-person-gear me-2" style="color:var(--accent)"></i>Manajemen Akun</h4>
        <small class="text-muted">Kelola semua akun pengguna sistem</small></div>
    <a href="/admin/users/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Akun</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="ps-3">#</th><th>Nama</th><th>Email</th><th>Role</th><th>Telepon</th><th>Status</th><th>Bergabung</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($users as $i => $u): ?>
            <tr>
                <td class="ps-3"><?= $i+1 ?></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width:30px;height:30px;font-size:.75rem;background:<?= ['admin'=>'#ef4444','pegawai'=>'#f59e0b','pelanggan'=>'#10b981'][$u->role]??'#6366f1' ?>">
                            <?= strtoupper(substr($u->nama,0,1)) ?>
                        </div>
                        <strong><?= e($u->nama) ?></strong>
                    </div>
                </td>
                <td><?= e($u->email) ?></td>
                <td>
                    <?php $roleColor = ['admin'=>'danger','pegawai'=>'warning','pelanggan'=>'success'][$u->role]??'secondary'; ?>
                    <span class="badge bg-<?= $roleColor ?>"><?= ucfirst(e($u->role)) ?></span>
                </td>
                <td><?= e($u->telepon ?: '-') ?></td>
                <td><?= $u->aktif ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
                <td class="text-muted" style="font-size:.8rem"><?= date('d/m/Y', strtotime($u->created_at)) ?></td>
                <td class="text-center">
                    <a href="/admin/users/edit?id=<?= $u->id ?>" class="btn btn-sm btn-outline-primary btn-action me-1"><i class="bi bi-pencil"></i></a>
                    <?php if ($u->id != auth()['id']): ?>
                    <form method="POST" action="/admin/users/delete" class="d-inline" onsubmit="return confirm('Hapus akun ini?')">
                        <input type="hidden" name="id" value="<?= $u->id ?>">
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
