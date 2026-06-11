<?php $pageTitle = 'Manajemen Pembayaran'; ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div><h4><i class="bi bi-credit-card me-2" style="color:var(--accent)"></i>Pembayaran</h4>
        <small>Kelola & konfirmasi transaksi pembayaran</small></div>
    <a href="/pembayaran/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Catat Pembayaran</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">#</th>
                    <th>Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pembayarans)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data pembayaran</td></tr>
                <?php else: ?>
                    <?php foreach ($pembayarans as $i => $p): ?>
                    <tr>
                        <td class="ps-3"><?= $i+1 ?></td>
                        <td><strong><?= e($p->kode_pesanan ?? '-') ?></strong></td>
                        <td><?= e($p->nama_pelanggan ?? '-') ?></td>
                        <td><span class="badge bg-light text-dark border"><?= e($p->metode ?? '-') ?></span></td>
                        <td><strong><?= formatRupiah($p->jumlah ?? 0) ?></strong></td>
                        <td>
                            <?php if ($p->status === 'menunggu_konfirmasi'): ?>
                                <span class="badge" style="background:#fef3c7;color:#92400e">⏳ Menunggu</span>
                            <?php elseif ($p->status === 'lunas'): ?>
                                <span class="badge bg-success">Lunas</span>
                            <?php elseif ($p->status === 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= e($p->status) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($p->status === 'menunggu_konfirmasi'): ?>
                                <form method="POST" action="/admin/pembayaran/konfirmasi" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $p->id ?>">
                                    <input type="hidden" name="status" value="lunas">
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi transfer?')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            <?php elseif ($p->status === 'pending'): ?>
                                <form method="POST" action="/pembayaran/prosesBayarKasir" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $p->id ?>">
                                    <div class="input-group input-group-sm">
                                        <select name="metode" class="form-select" required>
                                            <option value="Tunai">Tunai</option>
                                            <option value="QRIS">QRIS</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                        <button class="btn btn-primary" onclick="return confirm('Proses pembayaran kasir?')">Bayar</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <a href="/pembayaran/edit?id=<?= $p->id ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="/pembayaran/delete" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    <input type="hidden" name="id" value="<?= $p->id ?>">
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>