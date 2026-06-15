<style>
    /* Styling Kartu */
    .card-antrian {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }
    
    /* Header Tabel: Kembali ke warna terang & Rounded */
    .table thead th {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 1.2rem 1rem !important;
        font-weight: 700;
        vertical-align: middle;
        border-bottom: 2px solid #e9ecef;
    }

    /* Membuat sudut atas tabel rounded */
    .table thead tr th:first-child {
        border-top-left-radius: 16px;
    }
    .table thead tr th:last-child {
        border-top-right-radius: 16px;
    }
    
    /* Zebra Striping: Warna Hijau Mint Muda */
    .table-striped > tbody > tr:nth-of-type(even) {
        background-color: #f0fff4 !important; 
    }
    
    /* Border per baris */
    .table tbody tr {
        border-bottom: 1px solid #e1e8ed;
    }

    /* Efek Hover */
    .table-hover tbody tr:hover {
        background-color: #fffde7 !important;
        transition: all 0.2s ease;
    }

    .table td {
        padding: 1.2rem 1rem !important;
        vertical-align: middle;
    }
</style>

<?php $pageTitle = 'Antrian Pesanan'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task me-2 text-warning"></i>Antrian Pesanan</h4>
</div>

<div class="card card-antrian shadow-sm">
    <div class="card-body p-3 border-bottom bg-light">
        <div class="d-flex flex-wrap gap-2">
            <?php 
            $filters = [''=>'Semua', 'pending'=>'Pending', 'diproses'=>'Diproses', 'siap diambil'=>'Siap Diambil', 'selesai'=>'Selesai'];
            foreach ($filters as $v => $l): ?>
                <a href="/pegawai/antrian<?= $v ? "?status=$v" : '' ?>" 
                   class="btn btn-sm <?= $status===$v ? 'btn-warning text-white shadow-sm' : 'btn-white border-0 text-secondary' ?>">
                   <?= $l ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Detail Item & Catatan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pesanans as $p): $items = $detailMap[$p->id] ?? []; ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark"><?= e($p->kode_pesanan) ?></div>
                        <small class="text-muted"><i class="bi bi-clock me-1"></i><?= date('d M, H:i', strtotime($p->created_at)) ?></small>
                    </td>
                    <td>
                        <div class="fw-semibold text-primary"><?= e($p->nama_pelanggan) ?></div>
                        <span class="badge bg-white text-dark border"><i class="bi bi-grid-3x3-gap me-1"></i>Meja <?= e($p->nomor_meja) ?></span>
                    </td>
                    <td style="max-width: 250px;">
                        <ul class="list-unstyled mb-0 small">
                            <?php foreach ($items as $item): ?>
                                <li class="py-1">
                                    <span class="text-dark"><strong><?= e($item->nama_produk) ?></strong> x<?= (int)$item->jumlah ?></span>
                                    <?php if (!empty($item->catatan)): ?>
                                        <div class="text-info fst-italic"><small>Note: <?= e($item->catatan) ?></small></div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td class="fw-bold text-success fs-6"><?= formatRupiah((float)$p->total_harga) ?></td>
                    <td><?= statusBadge($p->status) ?></td>
                    <td>
                        <form method="POST" action="/pegawai/update-status" class="input-group input-group-sm">
                            <input type="hidden" name="id" value="<?= $p->id ?>">
                            <select name="status" class="form-select border-primary shadow-none">
                                <?php foreach (['pending', 'diproses', 'siap diambil', 'selesai', 'batal'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $p->status === $s ? 'selected' : '' ?>>
                                        <?= ucfirst(str_replace('_', ' ', $s)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>