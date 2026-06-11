<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Produk</h4>
    <a href="/produk/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
</div>

<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="/produk" class="btn btn-sm <?= empty($_GET['kategori_id']) ? 'btn-primary' : 'btn-outline-primary' ?>">All</a>
    <?php foreach ($kategoris as $k): ?>
        <a href="/produk?kategori_id=<?= $k->id ?>" 
           class="btn btn-sm <?= (($_GET['kategori_id'] ?? '') == $k->id) ? 'btn-primary' : 'btn-outline-primary' ?>">
           <?= e($k->nama) ?>
        </a>
    <?php endforeach; ?>
</div>

<form method="GET" action="/produk" class="mb-4">
    <div class="input-group shadow-sm">
        <input type="text" name="search" class="form-control" placeholder="Cari nama produk..." value="<?= e($_GET['search'] ?? '') ?>">
        <?php if(!empty($_GET['kategori_id'])): ?>
            <input type="hidden" name="kategori_id" value="<?= e($_GET['kategori_id']) ?>">
        <?php endif; ?>
        <button type="submit" class="btn btn-secondary">Cari</button>
    </div>
</form>

<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php if (empty($produks)): ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted">Produk tidak ditemukan.</p>
        </div>
    <?php else: ?>
        <?php foreach ($produks as $p): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div style="height: 250px; overflow: hidden;">
                    <img src="/uploads/produk/<?= e($p->gambar ?: 'default.jpg') ?>" 
                         class="card-img-top h-100 w-100" 
                         style="object-fit: cover; aspect-ratio: 1/1;">
                </div>
                
                <div class="card-body">
                    <h5 class="card-title fw-bold text-truncate"><?= e($p->nama) ?></h5>
                    
                    <?php
                    // Logika warna badge kategori dinamis
                    $kategori = $p->nama_kategori ?: 'Tanpa Kategori';
                    $badgeClass = match(strtolower($kategori)) {
                        'makanan' => 'bg-success',
                        'minuman' => 'bg-primary',
                        'snack'   => 'bg-warning text-dark',
                        default   => 'bg-info'
                    };
                    ?>
                    <span class="badge <?= $badgeClass ?> mb-2"><?= e($kategori) ?></span>
                    
                    <p class="text-secondary fw-semibold mb-1"><?= formatRupiah($p->harga) ?></p>
                    
                    <small class="text-muted">
                        Stok: <?= (int)$p->stok ?> pcs
                    </small>
                </div>
                
                <div class="card-footer bg-white border-0 d-flex gap-2">
                    <a href="/produk/edit?id=<?= $p->id ?>" class="btn btn-outline-warning btn-sm flex-fill">Edit</a>
                    
                    <form method="POST" action="/produk/delete" class="flex-fill" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                        <input type="hidden" name="id" value="<?= $p->id ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>