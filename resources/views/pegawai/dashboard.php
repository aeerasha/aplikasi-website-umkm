<?php $pageTitle = 'Dashboard Pegawai'; ?>

<div class="mb-4">
    <h4 class="fw-800" style="color:#1e293b"><i class="bi bi-speedometer2 me-2" style="color:#f59e0b"></i>Dashboard Pegawai</h4>
    <small class="text-muted">Halo, <strong><?= e(auth()['nama']) ?></strong> 👋 Siap bekerja hari ini?</small>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><div class="num"><?= $pesananHariIni ?></div><div class="lbl">Pesanan Hari Ini</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626)"><div class="num"><?= $pesananPending ?></div><div class="lbl">Menunggu Proses</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#4f46e5)"><div class="num"><?= $pesananDiproses ?></div><div class="lbl">Sedang Diproses</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669)"><div class="num"><?= $stokKritis ?></div><div class="lbl">Stok Kritis</div></div></div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-task me-2 text-warning"></i>Antrian Pesanan</span>
                <a href="/pegawai/antrian" class="btn btn-sm btn-warning text-white" style="font-size:.75rem">Kelola Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelAntrian">
                    <thead class="table-light">
                        <tr><th class="ps-3">Kode</th><th>Pelanggan</th><th>Produk</th><th>Status</th><th class="text-center">Aksi Cepat</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pesananAntrian as $p): ?>
                    <tr id="row-<?= $p->id ?>">
                        <td class="ps-3"><strong><?= e($p->kode_pesanan) ?></strong></td>
                        <td><?= e($p->nama_pelanggan) ?></td>
                        <td style="white-space:pre-line"><?= e($p->daftar_produk ?? '-') ?></td>
                        <td class="status-cell"><?= statusBadge($p->status) ?></td>
                        <td class="text-center">
                            <?php
                            $actions = [
                                'pending'      => ['next' => 'diproses', 'label' => 'Proses', 'class' => 'btn-primary', 'icon' => 'bi-play-fill'],
                                'diproses'     => ['next' => 'siap diambil', 'label' => 'Siap', 'class' => 'btn-info text-white', 'icon' => 'bi-bag-check-fill'],
                                'siap diambil' => ['next' => 'selesai', 'label' => 'Selesai', 'class' => 'btn-success', 'icon' => 'bi-check2-all']
                            ];
                            $act = $actions[$p->status] ?? null;
                            if ($act):
                            ?>
                            <button onclick="updateStatus(<?= $p->id ?>, '<?= $act['next'] ?>')" class="btn btn-sm <?= $act['class'] ?> shadow-sm" style="font-size: .75rem; font-weight: 600;">
                                <i class="bi <?= $act['icon'] ?> me-1"></i><?= $act['label'] ?>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Stok Kritis</div>
            <div class="card-body p-0">
                <?php if (empty($stokRendah)): ?><div class="text-center p-4 text-muted">Semua stok aman</div>
                <?php else: foreach ($stokRendah as $s): ?>
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom"><span><?= e($s->nama_bahan) ?></span><span class="badge bg-danger"><?= $s->jumlah ?></span></div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
async function updateStatus(id, status) {
    // 1. Kirim request ke server
    const response = await fetch('/pegawai/update-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&status=${status}`
    });

    if (response.ok) {
        // 2. Jika sukses, hapus baris atau reload bagian tabel saja
        // Cara simpel: refresh halaman agar angka statistik ikut terupdate
        window.location.reload(); 
    } else {
        alert('Gagal mengupdate status.');
    }
}
</script>