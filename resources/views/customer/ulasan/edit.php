<div class="container py-4">
    <div class="col-md-6 mx-auto">
        <div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Ubah Ulasan Anda</h5>
            <!-- FIXED: Tombol batal diarahkan kembali ke daftar ulasan customer -->
            <a href="/customer/ulasan" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">Batal</a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-body p-4">
                <!-- FIXED: Action diarahkan ke rute update customer yang baru -->
                <form action="/customer/ulasan/update" method="POST">
                    <input type="hidden" name="id" value="<?= $ulasanData->id ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.9rem;">Produk yang Diulas</label>
                        <input type="text" class="form-control bg-light text-muted" value="<?= e($ulasanData->nama_produk) ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.9rem;">Pembeli</label>
                        <input type="text" class="form-control bg-light text-muted" value="<?= e($ulasanData->nama_user ?? 'Pengguna Umum') ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="for-label fw-semibold" style="font-size: 0.9rem;">Ubah Rating</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="5" <?= $ulasanData->rating == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                            <option value="4" <?= $ulasanData->rating == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐ (4 - Bagus)</option>
                            <option value="3" <?= $ulasanData->rating == 3 ? 'selected' : '' ?>>⭐⭐⭐ (3 - Cukup)</option>
                            <option value="2" <?= $ulasanData->rating == 2 ? 'selected' : '' ?>>⭐⭐ (2 - Kurang)</option>
                            <option value="1" <?= $ulasanData->rating == 1 ? 'selected' : '' ?>>⭐ (1 - Buruk)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ulasan" class="form-label fw-semibold" style="font-size: 0.9rem;">Perbarui Isi Ulasan</label>
                        <!-- FIXED: Menggunakan fallback properti jika nama kolom di database bervariasi -->
                        <textarea class="form-control" id="ulasan" name="ulasan" rows="4" required><?= e($ulasanData->ulasan ?? $ulasanData->isi ?? $ulasanData->komentar ?? $ulasanData->text ?? '') ?></textarea>
                    </div>

                    <div class="form-check mb-4 bg-light p-3 rounded border border-dashed text-start">
                        <div class="ps-2">
                            <input class="form-check-input" type="checkbox" value="1" id="is_anonymous" name="is_anonymous" <?= $ulasanData->is_anonymous == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold text-secondary small ms-1" for="is_anonymous">
                                <i class="bi bi-incognito me-1 text-dark"></i> Tetapkan sebagai Anonim
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 9px;">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>