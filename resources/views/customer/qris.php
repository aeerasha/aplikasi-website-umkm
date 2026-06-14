<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS — <?= e($pesanan->kode_pesanan ?? '') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.10); max-width: 480px; width: 100%; }
        .card-header { background: linear-gradient(135deg,#10b981,#059669); border-radius: 16px 16px 0 0 !important; }
        .qr-box { border: 3px solid #1a1a1a; border-radius: 12px; background: #fff; padding: 16px; display: inline-block; }
        .btn-success { background: #10b981; border-color: #10b981; font-size: 1.05rem; }
        .btn-success:hover { background: #059669; }
        .price { font-size: 1.6rem; font-weight: 800; color: #10b981; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto">
        <div class="card-header text-white text-center py-4">
            <i class="bi bi-qr-code-scan fs-2 d-block mb-1"></i>
            <h5 class="mb-0 fw-bold">Pembayaran QRIS</h5>
            <small class="opacity-75">Pesanan <?= e($pesanan->kode_pesanan ?? '-') ?></small>
        </div>
        <div class="card-body text-center p-4">
            <p class="text-muted mb-1">Total yang harus dibayar:</p>
            <div class="price mb-3">
                <?= 'Rp ' . number_format($pesanan->total_harga ?? 0, 0, ',', '.') ?>
            </div>

            <!-- QRIS placeholder SVG -->
            <div class="qr-box mb-3">
                <svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <!-- Pojok kiri atas -->
                    <rect x="10" y="10" width="54" height="54" fill="none" stroke="#111" stroke-width="7" rx="4"/>
                    <rect x="22" y="22" width="30" height="30" fill="#111" rx="2"/>
                    <!-- Pojok kanan atas -->
                    <rect x="136" y="10" width="54" height="54" fill="none" stroke="#111" stroke-width="7" rx="4"/>
                    <rect x="148" y="22" width="30" height="30" fill="#111" rx="2"/>
                    <!-- Pojok kiri bawah -->
                    <rect x="10" y="136" width="54" height="54" fill="none" stroke="#111" stroke-width="7" rx="4"/>
                    <rect x="22" y="148" width="30" height="30" fill="#111" rx="2"/>
                    <!-- Pola tengah dummy -->
                    <rect x="80"  y="10"  width="10" height="10" fill="#111"/>
                    <rect x="96"  y="10"  width="10" height="10" fill="#111"/>
                    <rect x="112" y="10"  width="10" height="10" fill="#111"/>
                    <rect x="80"  y="26"  width="10" height="10" fill="#111"/>
                    <rect x="112" y="26"  width="10" height="10" fill="#111"/>
                    <rect x="80"  y="42"  width="10" height="10" fill="#111"/>
                    <rect x="96"  y="42"  width="10" height="10" fill="#111"/>
                    <rect x="80"  y="80"  width="10" height="10" fill="#111"/>
                    <rect x="96"  y="80"  width="10" height="10" fill="#111"/>
                    <rect x="112" y="80"  width="10" height="10" fill="#111"/>
                    <rect x="136" y="80"  width="10" height="10" fill="#111"/>
                    <rect x="152" y="80"  width="10" height="10" fill="#111"/>
                    <rect x="80"  y="96"  width="10" height="10" fill="#111"/>
                    <rect x="112" y="96"  width="10" height="10" fill="#111"/>
                    <rect x="152" y="96"  width="10" height="10" fill="#111"/>
                    <rect x="80"  y="112" width="10" height="10" fill="#111"/>
                    <rect x="96"  y="112" width="10" height="10" fill="#111"/>
                    <rect x="136" y="112" width="10" height="10" fill="#111"/>
                    <rect x="80"  y="136" width="10" height="10" fill="#111"/>
                    <rect x="96"  y="136" width="10" height="10" fill="#111"/>
                    <rect x="112" y="136" width="10" height="10" fill="#111"/>
                    <rect x="80"  y="152" width="10" height="10" fill="#111"/>
                    <rect x="112" y="152" width="10" height="10" fill="#111"/>
                    <rect x="80"  y="168" width="10" height="10" fill="#111"/>
                    <rect x="96"  y="168" width="10" height="10" fill="#111"/>
                    <rect x="136" y="136" width="10" height="10" fill="#111"/>
                    <rect x="152" y="136" width="10" height="10" fill="#111"/>
                    <rect x="136" y="152" width="10" height="10" fill="#111"/>
                    <rect x="152" y="168" width="10" height="10" fill="#111"/>
                    <rect x="168" y="136" width="10" height="10" fill="#111"/>
                    <rect x="168" y="152" width="10" height="10" fill="#111"/>
                    <rect x="168" y="80"  width="10" height="10" fill="#111"/>
                    <!-- Label -->
                    <text x="100" y="108" text-anchor="middle" font-size="9" fill="#888" font-family="sans-serif">QRIS DUMMY</text>
                </svg>
            </div>

            <p class="text-muted" style="font-size:.85rem; max-width:320px; margin:0 auto .75rem">
                Scan kode di atas menggunakan aplikasi pembayaran digital manapun, lalu klik tombol di bawah setelah selesai membayar.
            </p>

            <form method="POST" action="/customer/sudah-bayar">
                <input type="hidden" name="pesanan_id" value="<?= $pesanan->id ?? 0 ?>">
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mb-2">
                    <i class="bi bi-check2-circle me-2"></i>Saya Sudah Bayar
                </button>
            </form>
            <a href="/customer/katalog" class="btn btn-outline-secondary btn-sm w-100">
                Kembali ke Katalog
            </a>
        </div>
    </div>
</div>
</body>
</html>
