<?php $pageTitle = 'Rekap Penjualan'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-800 mb-0" style="color:#1a1f36">
            <i class="bi bi-bar-chart-line me-2" style="color:var(--accent)"></i>Rekap Penjualan
        </h4>
        <small class="text-muted">Laporan penjualan harian, mingguan & bulanan</small>
    </div>
    <?php
    $exportUrl = "/admin/rekap/export?periode=$periode&tanggal=$tanggal&minggu=$minggu&bulan=$bulan";
    ?>
    <a href="<?= $exportUrl ?>" class="btn btn-success">
        <i class="bi bi-download me-1"></i>Export CSV
    </a>
</div>

<!-- Filter Periode -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="/admin/rekap" class="row g-2 align-items-end">
            <!-- Tab periode -->
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <?php foreach (['harian'=>'Harian','mingguan'=>'Mingguan','bulanan'=>'Bulanan'] as $val=>$lbl): ?>
                    <a href="?periode=<?= $val ?>&tanggal=<?= $tanggal ?>&minggu=<?= $minggu ?>&bulan=<?= $bulan ?>"
                       class="btn <?= $periode===$val ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <?= $lbl ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Input sesuai periode -->
            <?php if ($periode === 'harian'): ?>
            <div class="col-auto">
                <label class="form-label fw-semibold mb-1" style="font-size:.8rem">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="<?= e($tanggal) ?>" onchange="this.form.submit()">
                <input type="hidden" name="periode" value="harian">
            </div>
            <?php elseif ($periode === 'mingguan'): ?>
            <div class="col-auto">
                <label class="form-label fw-semibold mb-1" style="font-size:.8rem">Minggu</label>
                <input type="week" name="minggu" class="form-control form-control-sm" value="<?= e($minggu) ?>" onchange="this.form.submit()">
                <input type="hidden" name="periode" value="mingguan">
            </div>
            <?php else: ?>
            <div class="col-auto">
                <label class="form-label fw-semibold mb-1" style="font-size:.8rem">Bulan</label>
                <input type="month" name="bulan" class="form-control form-control-sm" value="<?= e($bulan) ?>" onchange="this.form.submit()">
                <input type="hidden" name="periode" value="bulanan">
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #6366f1">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Total Transaksi</div>
            <div class="fw-800" style="font-size:1.8rem;color:#6366f1"><?= (int)($ringkasan->total_transaksi ?? 0) ?></div>
            <small class="text-muted">pesanan masuk</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #10b981">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Total Penjualan</div>
            <div class="fw-800" style="font-size:1.2rem;color:#10b981"><?= formatRupiah($ringkasan->total_penjualan ?? 0) ?></div>
            <small class="text-muted">nilai pesanan</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #f59e0b">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Kas Diterima</div>
            <div class="fw-800" style="font-size:1.2rem;color:#f59e0b"><?= formatRupiah($ringkasan->total_diterima ?? 0) ?></div>
            <small class="text-muted">pembayaran lunas</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #ef4444">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Rata-rata / Transaksi</div>
            <div class="fw-800" style="font-size:1.2rem;color:#ef4444"><?= formatRupiah($ringkasan->rata_rata ?? 0) ?></div>
            <small class="text-muted">per pesanan</small>
        </div>
    </div>
</div>

<!-- Status pesanan kecil -->
<div class="row g-2 mb-4">
    <?php
    $statusItems = [
        ['label'=>'Selesai',  'val'=>$ringkasan->pesanan_selesai??0, 'color'=>'#10b981','icon'=>'bi-check-circle'],
        ['label'=>'Pending',  'val'=>$ringkasan->pesanan_pending??0, 'color'=>'#f59e0b','icon'=>'bi-clock'],
        ['label'=>'Batal',    'val'=>$ringkasan->pesanan_batal??0,   'color'=>'#ef4444','icon'=>'bi-x-circle'],
        ['label'=>'Produk Terlaris','val'=>$ringkasan->produk_terlaris??'-','color'=>'#6366f1','icon'=>'bi-trophy'],
    ];
    ?>
    <?php foreach ($statusItems as $s): ?>
    <div class="col-6 col-md-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:<?= $s['color'] ?>20;flex-shrink:0">
                <i class="bi <?= $s['icon'] ?>" style="color:<?= $s['color'] ?>;font-size:1.1rem"></i>
            </div>
            <div>
                <div class="fw-bold" style="font-size:.95rem"><?= is_numeric($s['val']) ? $s['val'] : e($s['val']) ?></div>
                <div class="text-muted" style="font-size:.75rem"><?= $s['label'] ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3 mb-4">
    <!-- Grafik trend 12 bulan -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-graph-up me-2 text-primary"></i>Trend Penjualan (12 Bulan Terakhir)</div>
            <div class="card-body">
                <canvas id="grafikBulanan" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Metode pembayaran donut -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2 text-success"></i>Metode Pembayaran</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="grafikMetode" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Detail -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-table me-2"></i>
            <?php if ($periode === 'harian'): ?>
                Detail Transaksi — <?= date('d F Y', strtotime($tanggal)) ?>
            <?php elseif ($periode === 'mingguan'): ?>
                Rekap per Hari — Minggu <?= e($minggu) ?>
            <?php else: ?>
                Rekap per Minggu — <?= date('F Y', strtotime($bulan.'-01')) ?>
            <?php endif; ?>
        </span>
        <span class="badge bg-light text-dark border"><?= count($data) ?> baris</span>
    </div>

    <div class="table-responsive">
        <?php if (empty($data)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
            Tidak ada data untuk periode ini
        </div>
        <?php elseif ($periode === 'harian'): ?>
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3">Jam</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Jml</th>
                <th>Total</th>
                <th>Status</th>
                <th>Metode</th>
                <th>Bayar</th>
            </tr></thead>
            <tbody>
            <?php foreach ($data as $r): ?>
            <tr>
                <td class="ps-3 text-muted" style="font-size:.82rem"><?= e($r->jam) ?></td>
                <td><strong><?= e($r->kode_pesanan) ?></strong></td>
                <td><?= e($r->nama_pelanggan) ?></td>
                <td><?= e($r->nama_produk ?? '-') ?></td>
                <td><span class="badge bg-light text-dark border" style="font-size:.72rem"><?= e($r->kategori??'-') ?></span></td>
                <td><?= $r->jumlah ?></td>
                <td><strong><?= formatRupiah($r->total_harga) ?></strong></td>
                <td><?= statusBadge($r->status) ?></td>
                <td style="font-size:.82rem"><?= e($r->metode??'-') ?></td>
                <td><?= statusBadge($r->status_bayar??'pending') ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot style="background:#f8fafc">
                <tr>
                    <td colspan="6" class="ps-3 fw-bold">TOTAL</td>
                    <td class="fw-bold text-success"><?= formatRupiah(array_sum(array_column((array)$data,'total_harga'))) ?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        <?php else: /* mingguan / bulanan */ ?>
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3"><?= $periode==='mingguan' ? 'Tanggal' : 'Periode Minggu' ?></th>
                <th class="text-center">Transaksi</th>
                <th>Total Penjualan</th>
                <th>Kas Diterima</th>
                <th class="text-center">Selesai</th>
                <th class="text-center">Batal</th>
                <th>% Berhasil</th>
            </tr></thead>
            <tbody>
            <?php
            $grandTotal = 0; $grandDiterima = 0; $grandTrx = 0;
            foreach ($data as $r):
                $grandTotal    += $r->total_penjualan;
                $grandDiterima += $r->total_diterima;
                $grandTrx      += $r->total_transaksi;
                $pct = $r->total_transaksi > 0 ? round($r->selesai / $r->total_transaksi * 100) : 0;
            ?>
            <tr>
                <td class="ps-3 fw-semibold">
                    <?php if ($periode === 'mingguan'): ?>
                        <?= date('d M Y', strtotime($r->tanggal)) ?>
                    <?php else: ?>
                        Tgl <?= e($r->rentang ?? '-') ?>
                    <?php endif; ?>
                </td>
                <td class="text-center"><span class="badge bg-primary"><?= $r->total_transaksi ?></span></td>
                <td><?= formatRupiah($r->total_penjualan) ?></td>
                <td class="text-success fw-semibold"><?= formatRupiah($r->total_diterima) ?></td>
                <td class="text-center"><span class="badge bg-success"><?= $r->selesai ?></span></td>
                <td class="text-center"><span class="badge bg-danger"><?= $r->batal ?></span></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height:6px;border-radius:4px">
                            <div class="progress-bar bg-success" style="width:<?= $pct ?>%"></div>
                        </div>
                        <span style="font-size:.78rem;width:32px"><?= $pct ?>%</span>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot style="background:#f8fafc">
                <tr>
                    <td class="ps-3 fw-bold">TOTAL</td>
                    <td class="text-center fw-bold"><?= $grandTrx ?></td>
                    <td class="fw-bold text-primary"><?= formatRupiah($grandTotal) ?></td>
                    <td class="fw-bold text-success"><?= formatRupiah($grandDiterima) ?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
// ── Grafik Trend Bulanan ──────────────────────────────────────────────────────
const grafikData = <?= json_encode(array_map(fn($r) => [
    'bulan'      => date('M Y', strtotime($r->bulan.'-01')),
    'penjualan'  => (float)$r->penjualan,
    'transaksi'  => (int)$r->transaksi,
], $grafikBulanan)) ?>;

new Chart(document.getElementById('grafikBulanan'), {
    type: 'bar',
    data: {
        labels: grafikData.map(d => d.bulan),
        datasets: [
            {
                label: 'Penjualan (Rp)',
                data: grafikData.map(d => d.penjualan),
                backgroundColor: 'rgba(99,102,241,0.15)',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Transaksi',
                data: grafikData.map(d => d.transaksi),
                type: 'line',
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#10b981',
                pointRadius: 4,
                tension: 0.4,
                yAxisID: 'y1',
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
            y:  { position: 'left',  ticks: { callback: v => 'Rp '+v.toLocaleString('id-ID') } },
            y1: { position: 'right', grid: { drawOnChartArea: false }, ticks: { stepSize: 1 } }
        }
    }
});

// ── Grafik Metode Pembayaran ──────────────────────────────────────────────────
const metodeData = <?= json_encode(array_map(fn($r) => [
    'metode' => $r->metode ?? 'Lainnya',
    'jml'    => (int)$r->jml,
    'total'  => (float)$r->total,
], $metodeChart)) ?>;

new Chart(document.getElementById('grafikMetode'), {
    type: 'doughnut',
    data: {
        labels: metodeData.map(d => d.metode),
        datasets: [{
            data: metodeData.map(d => d.total),
            backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 } } },
            tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') } }
        },
        cutout: '65%'
    }
});
</script>
