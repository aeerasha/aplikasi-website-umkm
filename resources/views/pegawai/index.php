<?php $pageTitle = 'Manajemen Pegawai'; ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div><h4><i class="bi bi-people me-2" style="color:var(--accent)"></i>Pegawai</h4>
        <small>Kelola data pegawai</small></div>
    <a href="/pegawai/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Pegawai</a>
</div>
<div class="card">
    <div class="card-body pb-0">
        <form method="GET" action="/pegawai" class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau jabatan..." value="<?= e($search) ?>">
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Cari</button>
                <?php if ($search): ?><a href="/pegawai" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3">#</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Gaji</th>
                <th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php if (empty($pegawais)): ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data pegawai</td></tr>
            <?php else: ?>
            <?php foreach ($pegawais as $i => $p): ?>
            <tr>
                <td class="ps-3"><?= $i + 1 ?></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:var(--accent);font-size:.85rem">
                            <?= strtoupper(substr($p->nama, 0, 1)) ?>
                        </div>
                        <div><strong><?= e($p->nama) ?></strong><br><small class="text-muted"><?= e($p->alamat ?: '-') ?></small></div>
                    </div>
                </td>
                <td><span class="badge bg-light text-dark border"><?= e($p->jabatan ?: '-') ?></span></td>
                <td><?= e($p->email ?: '-') ?></td>
                <td><?= e($p->telepon ?: '-') ?></td>
                <td><?= formatRupiah($p->gaji ?? 0) ?></td>
                <td class="text-center">
                    <a href="/pegawai/edit?id=<?= $p->id ?>" class="btn btn-sm btn-outline-primary btn-action me-1"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="/pegawai/delete" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?')">
                        <input type="hidden" name="id" value="<?= $p->id ?>">
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white text-muted" style="font-size:.8rem;border-radius:0 0 16px 16px">Total: <strong><?= count($pegawais) ?></strong> pegawai</div>
</div>
