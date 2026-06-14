<?php $pageTitle = 'Antrian Pesanan'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-800 mb-0" style="color:#1e293b"><i class="bi bi-list-task me-2" style="color:#f59e0b"></i>Antrian Pesanan</h4></div>
</div>
<div class="card">
    <div class="card-body pb-0">
        <form method="GET" action="/pegawai/antrian" class="row g-2 mb-3">
            <div class="col-auto">
                <?php foreach ([''=>'Semua','pending'=>'Pending','menunggu_konfirmasi'=>'Menunggu Konfirmasi','diproses'=>'Diproses','selesai'=>'Selesai','batal'=>'Batal'] as $v => $l): ?>
                <a href="/pegawai/antrian<?= $v ? "?status=$v" : '' ?>"
                   class="btn btn-sm <?= $status===$v ? 'btn-warning text-white' : 'btn-outline-secondary' ?> me-1 mb-1"><?= $l ?></a>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-top mb-0">
            <thead>
                <tr>
                    <th class="ps-3">Kode & Waktu</th>
                    <th>Pelanggan</th>
                    <th>Item Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pesanans as $p):
                $items = $detailMap[$p->id] ?? [];
            ?>
            <tr>
                <td class="ps-3">
                    <strong><?= e($p->kode_pesanan) ?></strong><br>
                    <small class="text-muted"><?= date('d M H:i', strtotime($p->created_at)) ?></small>
                </td>
                <td>
                    <?= e($p->nama_pelanggan) ?><br>
                    <small class="text-muted"><?= e($p->telepon ?? '') ?></small>
                    <?php if (!empty($p->nomor_meja)): ?>
                        <br><span class="badge bg-info text-dark">Meja <?= e($p->nomor_meja) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (empty($items)): ?>
                        <span class="text-muted">—</span>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <div class="d-flex justify-content-between gap-3">
                                <span><?= e($item->nama_produk) ?> <span class="text-muted">×<?= $item->jumlah ?></span></span>
                                <span class="text-success fw-semibold text-nowrap"><?= formatRupiah($item->subtotal) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td class="fw-bold text-success"><?= formatRupiah($p->total_harga) ?></td>
                <td>
                    <?= statusBadge($p->status) ?>
                    <?php if (!empty($p->status_bayar)): ?>
                        <br><small><?= statusBadge($p->status_bayar) ?></small>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" action="/pegawai/update-status" class="d-flex gap-1">
                        <input type="hidden" name="id" value="<?= $p->id ?>">
                        <select name="status" class="form-select form-select-sm" style="width:130px"
                            <?= in_array($p->status, ['selesai','batal']) ? 'disabled' : '' ?>>
                            <?php foreach (['pending','menunggu_konfirmasi','diproses','selesai','batal'] as $s): ?>
                            <option value="<?= $s ?>" <?= $p->status===$s?'selected':'' ?>>
                                <?= ucfirst(str_replace('_',' ',$s)) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!in_array($p->status, ['selesai','batal'])): ?>
                            <button type="submit" class="btn btn-sm btn-warning text-white fw-bold">✓</button>
                        <?php else: ?>
                            <button disabled class="btn btn-sm btn-secondary">✓</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($pesanans)): ?>
            <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada pesanan</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
