<?php $pageTitle = 'Katalog Produk'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-800 mb-0" style="color:#1a1f36"><i class="bi bi-grid me-2" style="color:#10b981"></i>Katalog Produk</h4>
        <small class="text-muted">Pilih produk favorit Anda</small>
    </div>
</div>

<form method="GET" action="/customer/katalog" class="row g-2 mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text bg-white" style="border-color:#d1fae5"><i class="bi bi-search text-muted"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= e($search) ?>" style="border-left:none;border-color:#d1fae5">
        </div>
    </div>
    <div class="col-md-3">
        <select name="kategori" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <?php foreach (['Makanan','Minuman','Snack','Lainnya'] as $k): ?>
            <option value="<?= $k ?>" <?= $kategori===$k?'selected':'' ?>><?= $k ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <?php if ($search||$kategori): ?><a href="/customer/katalog" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
    </div>
</form>

<div class="row g-3">
<?php if (empty($produks)): ?>
    <div class="col-12 text-center py-5 text-muted">Tidak ada produk ditemukan</div>
<?php else: ?>
    <?php foreach ($produks as $p): ?>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="product-card card h-100">
            <div class="card-body p-3 d-flex flex-column">
                <div class="rounded-3 d-flex align-items-center justify-content-center mb-3" style="height:80px;background:<?= ($p->kategori??'')==='Minuman'?'#dbeafe':'#dcfce7' ?>">
                    <span style="font-size:2.2rem"><?= ($p->kategori??'')==='Minuman'?'🥤':(($p->kategori??'')==='Snack'?'🍿':'🍽️') ?></span>
                </div>
                
                <span class="badge mb-1 align-self-start" style="background:#d1fae5;color:#065f46;font-size:.7rem">
                    <?= e($p->kategori ?? 'Umum') ?>
                </span>
                
                <div class="fw-bold mb-1" style="font-size:.92rem"><?= e($p->nama) ?></div>
                <small class="text-muted mb-2" style="font-size:.78rem;flex-grow:1"><?= e($p->deskripsi??'') ?></small>
                
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <strong class="text-success"><?= formatRupiah($p->harga) ?></strong>
                    <small class="text-muted">Stok: <?= $p->stok ?></small>
                </div>
                <a href="/customer/buat-pesanan?produk_id=<?= $p->id ?>" class="btn btn-success btn-sm mt-2 w-100">Pesan</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
