<?php $pageTitle = 'Manajemen Pesanan'; ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div><h4><i class="bi bi-receipt me-2" style="color:var(--accent)"></i>Pesanan</h4>
        <small>Kelola data pesanan pelanggan</small></div>
    <a href="/pesanan/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Pesanan</a>
</div>
<div class="card">
    <div class="card-body pb-0">
        <form method="GET" action="/pesanan" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode pesanan..." value="<?= e($search) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <?php foreach (['pending','diproses','dikirim','selesai','batal'] as $s): ?>
                    <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Filter</button>
                <?php if ($search || $status): ?><a href="/pesanan" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3">#</th>
                <th>Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Jml</th>
                <th>Total</th>
                <th>Status</th>
                <th>Catatan</th>
                <th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php if (empty($pesanans)): ?>
                <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data pesanan</td></tr>
            <?php else: ?>
            <?php foreach ($pesanans as $i => $p): ?>
            <tr>
                <td class="ps-3"><?= $i + 1 ?></td>
                <td><strong><?= e($p->kode_pesanan) ?></strong></td>
                <td><?= e($p->nama_pelanggan) ?><br><small class="text-muted"><?= e($p->telepon ?: '') ?></small></td>
                <td><?= e($p->nama_produk ?? '-') ?></td>
                <td><?= $p->jumlah ?></td>
                <td><?= formatRupiah($p->total_harga) ?></td>
                <td><?= statusBadge($p->status) ?></td>
                <td class="text-muted" style="max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($p->catatan ?: '-') ?></td>
                <td class="text-center">
                    <a href="/pesanan/edit?id=<?= $p->id ?>" class="btn btn-sm btn-outline-primary btn-action me-1"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="/pesanan/delete" class="d-inline" onsubmit="return confirm('Hapus pesanan ini?')">
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
    <div class="card-footer bg-white text-muted" style="font-size:.8rem;border-radius:0 0 16px 16px">Total: <strong><?= count($pesanans) ?></strong> pesanan</div>
</div>
