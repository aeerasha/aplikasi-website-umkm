<div class="container py-4">
    <div class="col-md-6 mx-auto">
        <div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-pencil-fill me-2 text-primary"></i>Tulis Ulasan Baru</h5>
            <!-- FIXED: Tombol kembali diarahkan ke rute customer -->
            <a href="/customer/ulasan" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-body p-4">
                <!-- FIXED: Action diarahkan ke rute POST customer yang baru -->
                <form action="/customer/ulasan" method="POST">
                    
                    <div class="mb-3">
                        <label for="produk_id" class="form-label fw-semibold" style="font-size: 0.9rem;">Pilih Produk</label>
                        <select class="form-select" id="produk_id" name="produk_id" required>
                            <option value="" disabled selected>-- Pilih Produk yang Diulas --</option>
                            <?php foreach ($produkList as $p): ?>
                                <option value="<?= $p->id ?>"><?= e($p->nama_produk) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label fw-semibold" style="font-size: 0.9rem;">Berikan Rating</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                            <option value="2">⭐⭐ (2 - Kurang)</option>
                            <option value="1">⭐ (1 - Buruk)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ulasan" class="form-label fw-semibold" style="font-size: 0.9rem;">Isi Ulasan Anda</label>
                        <textarea class="form-control" id="ulasan" name="ulasan" rows="4" required placeholder="Ceritakan pengalaman Anda memakai produk ini..."></textarea>
                    </div>

                    <div class="form-check mb-4 bg-light p-3 rounded border border-dashed text-start">
                        <div class="ps-2">
                            <input class="form-check-input" type="checkbox" value="1" id="is_anonymous" name="is_anonymous">
                            <label class="form-check-label fw-semibold text-secondary small ms-1" for="is_anonymous">
                                <i class="bi bi-incognito me-1 text-dark"></i> Kirim sebagai Anonim
                            </label>
                            <div class="form-text text-muted mt-1" style="font-size: 0.75rem;">Nama Anda akan disembunyikan dari publik maupun halaman pemantauan admin.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 9px;">
                        <i class="bi bi-send me-1"></i> Kirim Ulasan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>