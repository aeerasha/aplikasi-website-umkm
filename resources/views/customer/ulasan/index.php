

<div class="container py-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Ulasan & Rating Produk</h4>
        <p class="text-muted small mb-0">Apa kata mereka tentang produk-produk UMKM App</p>
    </div>

        <div class="d-flex gap-2">
        <?php 
        // Menggunakan $_GET dan memastikan kita menangkap ID yang valid
        $pesanan_id = $_GET['pesanan_id'] ?? null; 
        ?>
        
        <?php if ($pesanan_id !== null): ?>
            <a href="/customer/pesanan-status?id=<?= (int)$pesanan_id ?>" class="btn btn-outline-secondary px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        <?php endif; ?>

        <a href="/customer/ulasan/create?pesanan_id=<?= (int)$pesanan_id ?>" class="btn btn-success px-3 fw-semibold">
            <i class="bi bi-pencil-square me-1"></i> Tulis Ulasan
        </a>
    </div>
</div>

    <div class="row">
        <?php if (empty($ulasan)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-chat-left-heart text-muted display-4"></i>
                <p class="text-muted mt-2">Belum ada ulasan.</p>
            </div>
        <?php else: ?>
            <?php foreach ($ulasan as $u): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        <?php if (isset($u->is_anonymous) && $u->is_anonymous == 1): ?>
                                            <span class="text-muted font-monospace" style="font-size: 0.9rem;">
                                                <i class="bi bi-eye-slash-fill me-1"></i>
                                                <?= (auth() && auth()['id'] === $u->user_id) ? 'Anonim (Anda)' : 'Anonim' ?>
                                            </span>
                                        <?php else: ?>
                                            <?= e($u->nama_user ?? 'Pengguna Umum') ?>
                                            <?php if (auth() && auth()['id'] === $u->user_id): ?>
                                                <span class="badge bg-primary ms-1" style="font-size: 0.6rem;">Anda</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </h6>
                                    <span class="badge bg-light text-success border mt-1">
                                        <i class="bi bi-box-seam me-1"></i><?= e($u->nama_produk ?? 'Produk') ?>
                                    </span>
                                </div>
                                
                                <div class="text-warning">
                                    <?php 
                                    $stars = (int)($u->rating ?? 0);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $stars ? '<i class="bi bi-star-fill me-1"></i>' : '<i class="bi bi-star text-muted me-1"></i>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <p class="card-text text-secondary my-3 flex-grow-1" style="font-size: 0.88rem; font-style: italic;">
                                "<?= e($u->ulasan ?? $u->isi ?? $u->komentar ?? $u->text ?? 'Tidak ada ulasan') ?>"
                            </p>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top border-light mt-auto">
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($u->created_at ?? date('Y-m-d'))) ?>
                                </small>

                                <?php if (auth() && auth()['id'] === $u->user_id): ?>
                                    <div class="d-flex gap-2">
                                        <a href="/customer/ulasan/edit?id=<?= $u->id ?>" class="btn btn-sm btn-outline-warning py-1 px-2">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="/customer/ulasan/delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?')">
                                            <input type="hidden" name="id" value="<?= $u->id ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>