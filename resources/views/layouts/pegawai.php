<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($pageTitle ?? 'Pegawai') ?> — UMKM App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --sb:#1e293b; --sbw:250px; --accent:#f59e0b; }
        body { font-family:'Segoe UI',sans-serif; background:#f4f6fb; min-height:100vh; }
        #sidebar { width:var(--sbw); min-height:100vh; background:var(--sb); position:fixed; top:0; left:0; z-index:100; overflow-y:auto; }
        #sidebar .brand { padding:20px; border-bottom:1px solid rgba(255,255,255,.08); }
        #sidebar .brand h5 { color:#fff; font-weight:800; margin:0; font-size:.95rem; }
        #sidebar .nav-label { color:#64748b; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; padding:16px 20px 5px; }
        #sidebar .nav-link { color:#cbd5e1; padding:9px 18px; border-radius:8px; margin:1px 10px; display:flex; align-items:center; gap:9px; font-size:.87rem; text-decoration:none; transition:all .2s; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background:rgba(245,158,11,.18); color:#fde68a; }
        #main { margin-left:var(--sbw); min-height:100vh; }
        #topbar { background:#fff; border-bottom:1px solid #e8ecf2; padding:12px 24px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:99; }
        .content-area { padding:24px; }
        .card { border:none; border-radius:14px; box-shadow:0 2px 14px rgba(0,0,0,.06); }
        .card-header { background:#fff; border-bottom:1px solid #f0f0f0; border-radius:14px 14px 0 0!important; padding:14px 18px; font-weight:700; }
        .table th { font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b; font-weight:700; }
        .table td { vertical-align:middle; font-size:.87rem; }
        .stat-card { border:none; border-radius:14px; padding:18px; color:#fff; }
        .stat-card .num { font-size:1.7rem; font-weight:800; }
        .stat-card .lbl { font-size:.78rem; opacity:.9; }
        .badge { border-radius:7px; font-weight:600; font-size:.73rem; padding:4px 9px; }
        .form-control,.form-select { border-radius:9px; border:1.5px solid #e2e8f0; padding:9px 13px; }
        .form-control:focus,.form-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(245,158,11,.13); }
        .btn-warning { background:var(--accent); border-color:var(--accent); color:#fff; border-radius:9px; font-weight:600; }
    </style>
</head>
<body>
<?php $curUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); $u = auth(); ?>
<nav id="sidebar">
    <div class="brand">
        <h5><i class="bi bi-person-badge me-2" style="color:#f59e0b"></i>Portal Pegawai</h5>
        <small style="color:#94a3b8;font-size:.75rem"><?= e($u['nama'] ?? '') ?></small>
    </div>
    <div class="nav-label">Menu</div>
    <a href="/pegawai/dashboard"  class="nav-link <?= $curUri==='/pegawai/dashboard'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="/pegawai/antrian"    class="nav-link <?= str_starts_with($curUri,'/pegawai/antrian')?'active':'' ?>"><i class="bi bi-list-task"></i> Antrian Pesanan</a>
    <a href="/pegawai/stok-view"  class="nav-link <?= $curUri==='/pegawai/stok-view'?'active':'' ?>"><i class="bi bi-archive"></i> Lihat Stok</a>
    <div class="nav-label">Akun</div>
    <a href="/pegawai/profile"    class="nav-link <?= $curUri==='/pegawai/profile'?'active':'' ?>"><i class="bi bi-person-circle"></i> Profil Saya</a>
    <a href="/logout" class="nav-link" style="color:#ef4444"><i class="bi bi-box-arrow-left"></i> Logout</a>
</nav>
<div id="main">
    <div id="topbar">
        <div style="font-weight:700;color:#1e293b;font-size:.95rem"><i class="bi bi-grid-fill me-2" style="color:#f59e0b"></i><?= e($pageTitle ?? 'Dashboard') ?></div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:#fef3c7;color:#d97706">PEGAWAI</span>
            <span style="font-size:.82rem;font-weight:600"><?= e($u['nama'] ?? '') ?></span>
        </div>
    </div>
    <div class="content-area">
        <?php if ($f=getFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 mb-3" style="border-radius:10px"><i class="bi bi-check-circle-fill me-2"></i><?= e($f) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($f=getFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 mb-3" style="border-radius:10px"><i class="bi bi-exclamation-circle-fill me-2"></i><?= e($f) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php include $content; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
