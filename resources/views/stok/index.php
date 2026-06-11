<?php $pageTitle = 'Stok Bahan Baku'; ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div><h4><i class="bi bi-archive me-2" style="color:var(--accent)"></i>Stok Bahan Baku</h4>
        <small>Kelola stok bahan baku produksi</small></div>
    <a href="/stok/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Stok</a>
</div>
<div class="card">
    <div class="card-body pb-0">
        <form method="GET" action="/stok" class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama bahan atau supplier..." value="<?= e($search) ?>">
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Cari</button>
                <?php if ($search): ?><a href="/stok" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3">#</th>
                <th>Nama Bahan</th>
                <th>Satuan</th>
                <th>Jumlah</th>
                <th>Stok Min</th>
                <th>Harga/Satuan</th>
                <th>Supplier</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php if (empty($stoks)): ?>
                <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data stok</td></tr>
            <?php else: ?>
            <?php foreach ($stoks as $i => $s):
                $statusClass = $s->jumlah <= $s->stok_minimum ? 'danger' : ($s->jumlah <= ($s->stok_minimum * 1.5) ? 'warning text-dark' : 'success');
                $statusText  = $s->jumlah <= $s->stok_minimum ? 'Kritis' : ($s->jumlah <= ($s->stok_minimum * 1.5) ? 'Rendah' : 'Aman');
            ?>
            <tr>
                <td class="ps-3"><?= $i + 1 ?></td>
                <td><strong><?= e($s->nama_bahan) ?></strong></td>
                <td><?= e($s->satuan) ?></td>
                <td><strong><?= $s->jumlah ?></strong></td>
                <td class="text-muted"><?= $s->stok_minimum ?></td>
                <td><?= formatRupiah($s->harga_satuan ?? 0) ?></td>
                <td><?= e($s->supplier ?: '-') ?></td>
                <td><span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span></td>
                <td class="text-center">
                    <a href="/stok/edit?id=<?= $s->id ?>" class="btn btn-sm btn-outline-primary btn-action me-1"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="/stok/delete" class="d-inline" onsubmit="return confirm('Hapus data stok ini?')">
                        <input type="hidden" name="id" value="<?= $s->id ?>">
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white text-muted" style="font-size:.8rem;border-radius:0 0 16px 16px">Total: <strong><?= count($stoks) ?></strong> bahan baku</div>
</div>
