<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar — UMKM App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background:linear-gradient(135deg,#1a1f36,#2d3561); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .card { border:none; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.3); max-width:460px; width:100%; }
        .card-header { background:linear-gradient(135deg,#10b981,#059669); border-radius:20px 20px 0 0 !important; padding:28px; text-align:center; }
        .form-control { border-radius:10px; border:1.5px solid #e2e8f0; padding:10px 14px; }
        .form-control:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
        .btn-register { background:linear-gradient(135deg,#10b981,#059669); border:none; border-radius:10px; padding:12px; font-weight:700; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <div style="font-size:2rem;margin-bottom:6px">👤</div>
        <h5 class="text-white fw-800 mb-0">Daftar Akun Pelanggan</h5>
        <small class="text-white opacity-75">Buat akun untuk mulai berbelanja</small>
    </div>
    <div class="card-body p-4">
        <?php if ($err = ($_SESSION['reg_error'] ?? null)): unset($_SESSION['reg_error']); ?>
            <div class="alert alert-danger border-0 rounded-3 py-2 mb-3" style="font-size:.85rem"><?= e($err) ?></div>
        <?php endif; ?>
        <form method="POST" action="/register">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">No. Telepon</label>
                <input type="text" name="telepon" class="form-control" placeholder="081234567890">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
            </div>
            <button type="submit" class="btn btn-register text-white w-100 mb-3">
                <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
            </button>
        </form>
        <div class="text-center" style="font-size:.85rem">
            Sudah punya akun? <a href="/login" style="color:#6366f1;font-weight:600">Login di sini</a>
        </div>
    </div>
</div>
</body>
</html>
