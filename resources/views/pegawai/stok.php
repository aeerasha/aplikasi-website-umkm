<?php $pageTitle = 'Stok Bahan Baku'; ?>
<div class="mb-4"><h4 class="fw-800" style="color:#1e293b"><i class="bi bi-archive me-2" style="color:#f59e0b"></i>Stok Bahan Baku</h4>
<small class="text-muted">Informasi stok bahan baku produksi</small></div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="ps-3">#</th><th>Nama Bahan</th><th>Satuan</th><th>Stok</th><th>Min</th><th>Supplier</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($stoks as $i => $s):
                $kritis = $s->jumlah <= $s->stok_minimum;
                $rendah = !$kritis && $s->jumlah <= ($s->stok_minimum * 1.5);
            ?>
            <tr>
                <td class="ps-3"><?= $i+1 ?></td>
                <td><strong><?= e($s->nama_bahan) ?></strong></td>
                <td><?= e($s->satuan) ?></td>
                <td class="<?= $kritis ? 'text-danger fw-bold' : ($rendah ? 'text-warning fw-bold' : '') ?>"><?= $s->jumlah ?></td>
                <td class="text-muted"><?= $s->stok_minimum ?></td>
                <td><?= e($s->supplier??'-') ?></td>
                <td>
                    <?php if ($kritis): ?><span class="badge bg-danger">Kritis</span>
                    <?php elseif ($rendah): ?><span class="badge bg-warning text-dark">Rendah</span>
                    <?php else: ?><span class="badge bg-success">Aman</span><?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
