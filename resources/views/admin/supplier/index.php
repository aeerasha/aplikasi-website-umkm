<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-truck me-2 text-primary"></i>Daftar Supplier / Vendor</h4>
    <a href="/supplier/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Supplier
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-3">No</th>
                        <th>Nama Supplier</th>
                        <th>Nama Kontak (PIC)</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Keterangan</th>
                        <th width="15%" class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($suppliers)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data supplier.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($suppliers as $supp): ?>
                            <tr>
                                <td class="ps-3"><?= $no++ ?></td>
                                <td><strong><?= e($supp->nama_supplier) ?></strong></td>
                                <td><?= e($supp->nama_kontak) ?></td>
                                <td><span class="text-secondary"><i class="bi bi-whatsapp me-1 text-success"></i><?= e($supp->telepon) ?></span></td>
                                <td><?= e($supp->alamat) ?></td>
                                <td><small class="text-muted"><?= e($supp->keterangan ?? '-') ?></small></td>
                                <td class="text-center pe-3">
                                    <a href="/supplier/edit?id=<?= $supp->id ?>" class="btn btn-sm btn-action btn-warning text-white">Edit</a>
                                    <form action="/supplier/delete" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                        <input type="hidden" name="id" value="<?= $supp->id ?>">
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