<div class="container py-4">
    <div class="card p-4 mx-auto" style="max-width: 400px; border-radius: 12px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h5 class="text-center mb-3">Pembayaran QRIS</h5>
        
        <div class="text-center">
            <p class="text-muted mb-1">Total Tagihan:</p>
            <div class="mb-3" style="font-size: 1.5rem; font-weight: bold; color: #000;">
                Rp <?= number_format($total ?? 0, 0, ',', '.') ?>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center border rounded" 
             style="height: 200px; background: #eee; margin: 1rem 0; border: 1px solid #ddd;">
            <span class="text-muted">QRIS CODE</span>
        </div>

        <form method="POST" action="/customer/final-store">
            <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                Bayar Sekarang
            </button>
        </form>

        <a href="/customer/checkout" class="btn btn-link w-100 mt-2 text-decoration-none text-dark small">
            Kembali
        </a>
    </div>
</div>