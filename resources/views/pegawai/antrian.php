<?php $pageTitle = 'Antrian Pesanan'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-800 mb-0" style="color:#1e293b"><i class="bi bi-list-task me-2" style="color:#f59e0b"></i>Antrian Pesanan</h4></div>
</div>
<div class="card">
    <div class="card-body pb-0">
        <form method="GET" action="/pegawai/antrian" class="row g-2 mb-3">
            <div class="col-auto">
                <?php foreach ([''=>'Semua','pending'=>'Pending','diproses'=>'Diproses','dikirim'=>'Dikirim','selesai'=>'Selesai'] as $v => $l): ?>
                <a href="/pegawai/antrian<?= $v ? "?status=$v" : '' ?>" class="btn btn-sm <?= $status===$v ? 'btn-warning text-white' : 'btn-outline-secondary' ?> me-1"><?= $l ?></a>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="ps-3">Kode</th><th>Pelanggan</th><th>Produk</th><th>Jml</th><th>Total</th><th>Catatan</th><th>Status</th><th>Ubah Status</th></tr></thead>
            <tbody>
            <?php foreach ($pesanans as $p):
                $next = ['pending'=>'diproses','diproses'=>'dikirim','dikirim'=>'selesai'];
                $nextStatus = $next[$p->status] ?? null;
            ?>
            <tr>
                <td class="ps-3"><strong><?= e($p->kode_pesanan) ?></strong></td>
                <td><?= e($p->nama_pelanggan) ?><br><small class="text-muted"><?= e($p->telepon??'') ?></small></td>
                <td><?= e($p->nama_produk??'-') ?></td>
                <td><?= $p->jumlah ?></td>
                <td><?= formatRupiah($p->total_harga) ?></td>
                <td class="text-muted" style="max-width:100px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($p->catatan??'-') ?></td>
                <td><?= statusBadge($p->status) ?></td>
                <td>
                    <form method="POST" action="/pegawai/update-status" class="d-flex gap-1">
                        <input type="hidden" name="id" value="<?= $p->id ?>">
                        <select name="status" class="form-select form-select-sm" style="width:110px">
                            <?php foreach (['pending','diproses','dikirim','selesai','batal'] as $s): ?>
                            <option value="<?= $s ?>" <?= $p->status===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-warning text-white">✓</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($pesanans)): ?>
            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada pesanan</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
