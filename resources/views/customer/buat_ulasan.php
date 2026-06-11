<?php $pageTitle = 'Beri Ulasan'; ?>
<div class="mb-4">
    <a href="/customer/ulasan-saya" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <h4 class="fw-800 mb-0" style="color:#1a1f36"><i class="bi bi-star-fill me-2" style="color:#f59e0b"></i>Beri Ulasan</h4>
    <small class="text-muted">Bagikan pengalaman Anda</small>
</div>
<div class="row"><div class="col-md-6 col-lg-5">
<div class="card"><div class="card-body p-4">
    <form method="POST" action="/customer/buat-ulasan">
        <div class="mb-3">
            <label class="form-label fw-semibold">Produk</label>
            <select name="produk_id" class="form-select" required>
                <option value="">-- Pilih Produk --</option>
                <?php foreach ($produks as $p): ?>
                <option value="<?= $p->id ?>"><?= e($p->nama) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Rating</label>
            <div class="d-flex gap-3 mt-1">
                <?php for ($r = 1; $r <= 5; $r++): ?>
                <div class="form-check form-check-inline m-0">
                    <input class="form-check-input d-none" type="radio" name="rating" id="r<?= $r ?>" value="<?= $r ?>" <?= $r===5?'checked':'' ?>>
                    <label class="form-check-label fs-4 cursor-pointer" for="r<?= $r ?>" style="cursor:pointer"><?= str_repeat('★',$r) ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <small class="text-muted">Pilih 1–5 bintang</small>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Komentar</label>
            <textarea name="komentar" class="form-control" rows="3" placeholder="Ceritakan pengalaman Anda..."></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100 py-2 fw-bold"><i class="bi bi-send me-2"></i>Kirim Ulasan</button>
    </form>
</div></div>
</div></div>
