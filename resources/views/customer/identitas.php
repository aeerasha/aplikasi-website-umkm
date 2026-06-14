<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Pelanggan — UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.10); max-width: 420px; width: 100%; }
        .card-header { background: linear-gradient(135deg,#10b981,#059669); border-radius: 16px 16px 0 0 !important; }
        .btn-success { background: #10b981; border-color: #10b981; }
        .btn-success:hover { background: #059669; border-color: #059669; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto">
        <div class="card-header text-white text-center py-4">
            <i class="bi bi-person-badge fs-2 d-block mb-1"></i>
            <h5 class="mb-0 fw-bold">Selamat Datang!</h5>
            <small class="opacity-75">Isi identitas Anda untuk mulai memesan</small>
        </div>
        <div class="card-body p-4">
            <?php if ($msg = getFlash('error')): ?>
                <div class="alert alert-danger py-2"><?= e($msg) ?></div>
            <?php endif; ?>
            <form method="POST" action="/customer/identitas">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. HP / WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 0812xxxxxxxx" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nomor Meja <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_meja" class="form-control" placeholder="Contoh: 5" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                    <i class="bi bi-arrow-right-circle me-2"></i>Mulai Pesan
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
