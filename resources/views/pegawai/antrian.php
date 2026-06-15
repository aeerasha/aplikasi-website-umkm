<?php $pageTitle = 'Antrian Pesanan'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task me-2 text-warning"></i>Antrian Pesanan</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body pb-0">
        <div class="mb-3">
            <?php 
            // 1. Tambahkan 'siap diambil' ke dalam array filter
            $filters = [''=>'Semua', 'pending'=>'Pending', 'diproses'=>'Diproses', 'siap diambil'=>'Siap Diambil', 'selesai'=>'Selesai'];
            foreach ($filters as $v => $l): ?>
                <a href="/pegawai/antrian<?= $v ? "?status=$v" : '' ?>" 
                   class="btn btn-sm <?= $status===$v ? 'btn-warning text-white' : 'btn-outline-secondary' ?> me-1 mb-1">
                   <?= $l ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Detail Item & Catatan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pesanans as $p): $items = $detailMap[$p->id] ?? []; ?>
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold text-primary"><?= e($p->kode_pesanan) ?></div>
                        <small class="text-muted"><?= date('d M, H:i', strtotime($p->created_at)) ?></small>
                    </td>
                    <td>
                        <div class="fw-semibold"><?= e($p->nama_pelanggan) ?></div>
                        <span class="badge bg-info text-dark">Meja <?= e($p->nomor_meja) ?></span>
                    </td>
                    <td>
                        <ul class="list-unstyled mb-0 small">
                            <?php foreach ($items as $item): ?>
                                <li class="border-bottom py-1">
                                    <strong><?= e($item->nama_produk) ?></strong> x<?= (int)$item->jumlah ?>
                                    <?php if (!empty($item->catatan)): ?>
                                        <div class="text-info fst-italic">Note: <?= e($item->catatan) ?></div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td class="fw-bold text-success"><?= formatRupiah((float)$p->total_harga) ?></td>
                    <td><?= statusBadge($p->status) ?></td>
                    <td>
                        <form method="POST" action="/pegawai/update-status" class="input-group input-group-sm">
                            <input type="hidden" name="id" value="<?= $p->id ?>">
                            <select name="status" class="form-select border-primary">
                                <?php foreach (['pending', 'diproses', 'siap diambil', 'selesai', 'batal'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $p->status === $s ? 'selected' : '' ?>>
                                        <?= ucfirst(str_replace('_', ' ', $s)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>