<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-tools me-2 text-primary"></i>Manajemen Inventaris / Alat</h4>
    <a href="/inventaris/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Alat
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-3">No</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th width="15%" class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inventaris)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data inventaris.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($inventaris as $alat): ?>
                            <tr>
                                <td class="ps-3"><?= $no++ ?></td>
                                <td><strong><?= e($alat->nama_alat) ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?= e($alat->kategori) ?></span></td>
                                <td><?= e($alat->jumlah) ?> unit</td>
                                <td>
                                    <?php 
                                    $badge = $alat->kondisi === 'Baik' ? 'success' : ($alat->kondisi === 'Rusak' ? 'danger' : 'warning');
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= e($alat->kondisi) ?></span>
                                </td>
                                <td class="text-center pe-3">
                                    <a href="/inventaris/edit?id=<?= $alat->id ?>" class="btn btn-sm btn-action btn-warning text-white">Edit</a>
                                    <form action="/inventaris/delete" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                                        <input type="hidden" name="id" value="<?= $alat->id ?>">
                                        <button type="submit" class="btn btn-sm btn-action btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>