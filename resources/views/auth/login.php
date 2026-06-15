<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login — UMKM App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg,#1a1f36 0%,#2d3561 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .login-card { background:#fff; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.3); overflow:hidden; width:100%; max-width:420px; }
        .login-header { background:linear-gradient(135deg,#6366f1,#8b5cf6); padding:32px; text-align:center; }
        .login-header h4 { color:#fff; font-weight:800; margin:0; }
        .login-header small { color:rgba(255,255,255,.7); }
        .login-body { padding:32px; }
        .form-control { border-radius:10px; border:1.5px solid #e2e8f0; padding:10px 14px; }
        .form-control:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.15); }
        .btn-login { background:linear-gradient(135deg,#6366f1,#8b5cf6); border:none; border-radius:10px; padding:12px; font-weight:700; }
        .role-hint { background:#f8fafc; border-radius:12px; padding:14px; font-size:.8rem; }
        .role-hint .role-row { display:flex; justify-content:space-between; padding:4px 0; border-bottom:1px solid #eee; }
        .role-hint .role-row:last-child { border-bottom:none; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div style="font-size:2.5rem;margin-bottom:8px">🛒</div>
        <h4>UMKM MAJU</h4>
        <small>Pengembangan Aplikasi untuk UMKM</small>
    </div>
    <div class="login-body">
        <?php if ($err = ($_SESSION['auth_error'] ?? null)): unset($_SESSION['auth_error']); ?>
            <div class="alert alert-danger border-0 rounded-3 py-2 mb-3" style="font-size:.85rem"><i class="bi bi-exclamation-circle me-1"></i><?= e($err) ?></div>
        <?php endif; ?>
        <?php if ($ok = ($_SESSION['auth_success'] ?? null)): unset($_SESSION['auth_success']); ?>
            <div class="alert alert-success border-0 rounded-3 py-2 mb-3" style="font-size:.85rem"><i class="bi bi-check-circle me-1"></i><?= e($ok) ?></div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="email@contoh.com" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login text-white w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
            </button>
        </form>

        <div class="text-center mb-3" style="font-size:.85rem">
        </div>

        <div class="role-hint">
            <div class="fw-bold mb-2 text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Akun Demo</div>
            <div class="role-row"><span><i class="bi bi-shield-check text-danger me-1"></i><strong>Admin</strong></span><span class="text-muted">admin@umkm.com / admin123</span></div>
            <div class="role-row"><span><i class="bi bi-person-badge text-warning me-1"></i><strong>Pegawai</strong></span><span class="text-muted">budi@umkm.com / pegawai123</span></div>
        </div>
    </div>
</div>
</body>
</html>
