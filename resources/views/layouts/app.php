<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> — UMKM App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --sb:#1a1f36; --sbw:260px; --accent:#6366f1; --accent2:#4f46e5; }
        body { font-family:'Segoe UI',sans-serif; background:#f4f6fb; min-height:100vh; }
        #sidebar { width:var(--sbw); height:100vh; background:var(--sb); position:fixed; top:0; left:0; z-index:100; overflow-y:auto; }
        #sidebar .brand { padding:22px 20px 14px; border-bottom:1px solid rgba(255,255,255,.08); }
        #sidebar .brand h5 { color:#fff; font-weight:800; margin:0; font-size:1rem; }
        #sidebar .brand small { color:#a5b4fc; font-size:.72rem; }
        #sidebar .nav-label { color:#6b7db3; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; padding:18px 20px 5px; }
        #sidebar .nav-link { color:#cbd5e1; padding:9px 18px; border-radius:8px; margin:1px 10px; display:flex; align-items:center; gap:9px; font-size:.88rem; text-decoration:none; transition:all .2s; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background:rgba(99,102,241,.18); color:#a5b4fc; }
        #sidebar .nav-link i { font-size:.95rem; width:18px; text-align:center; }
        #main { margin-left:var(--sbw); min-height:100vh; }
        #topbar { background:#fff; border-bottom:1px solid #e8ecf2; padding:12px 26px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:99; }
        .content-area { padding:24px 28px; }
        .card { border:none; border-radius:14px; box-shadow:0 2px 14px rgba(0,0,0,.06); }
        .card-header { background:#fff; border-bottom:1px solid #f0f0f0; border-radius:14px 14px 0 0 !important; padding:15px 20px; font-weight:700; }
        .stat-card { border:none; border-radius:14px; padding:18px; color:#fff; transition:transform .2s; }
        .stat-card:hover { transform:translateY(-2px); }
        .stat-card .num { font-size:1.7rem; font-weight:800; }
        .stat-card .lbl { font-size:.78rem; opacity:.9; }
        .table th { font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; color:#6b7db3; font-weight:700; border-bottom:2px solid #f0f0f0; }
        .table td { vertical-align:middle; font-size:.88rem; }
        .btn-action { border-radius:7px; padding:4px 10px; font-size:.78rem; }
        .form-control,.form-select { border-radius:9px; border:1.5px solid #e2e8f0; padding:9px 13px; }
        .form-control:focus,.form-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.13); }
        .btn-primary { background:var(--accent); border-color:var(--accent); border-radius:9px; }
        .btn-primary:hover { background:var(--accent2); border-color:var(--accent2); }
        .btn-outline-primary { border-color:var(--accent); color:var(--accent); border-radius:9px; }
        .page-header { margin-bottom:22px; }
        .page-header h4 { font-weight:800; color:#1a1f36; margin:0; }
        .badge { border-radius:7px; font-weight:600; font-size:.73rem; padding:4px 9px; }
        .role-badge-admin   { background:#fef2f2; color:#ef4444; border:1px solid #fecaca; border-radius:6px; font-size:.72rem; font-weight:700; padding:2px 8px; }
        .role-badge-pegawai { background:#fefce8; color:#d97706; border:1px solid #fde68a; border-radius:6px; font-size:.72rem; font-weight:700; padding:2px 8px; }
    </style>
</head>
<body>
<?php $curUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); $u = auth(); ?>
<nav id="sidebar">
    <div class="brand">
        <h5><i class="bi bi-shop me-2"></i>UMKM App</h5>
        <small>
            <?php if ($u): ?>
                <span class="role-badge-<?= $u['role'] ?>"><?= strtoupper($u['role']) ?></span>
                <?= e($u['nama']) ?>
            <?php endif; ?>
        </small>
    </div>

    <div class="nav-label">Utama</div>
    <a href="/admin/dashboard" class="nav-link <?= $curUri==='/admin/dashboard'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>

    <div class="nav-label">Manajemen</div>
    <a href="/produk"  class="nav-link <?= str_starts_with($curUri,'/produk')?'active':'' ?>"><i class="bi bi-box-seam"></i> Produk</a>
    <a href="/pegawai" class="nav-link <?= str_starts_with($curUri,'/pegawai')?'active':'' ?>"><i class="bi bi-people"></i> Pegawai</a>
    <a href="/stok"    class="nav-link <?= str_starts_with($curUri,'/stok')?'active':'' ?>"><i class="bi bi-archive"></i> Stok Bahan Baku</a>
    <a href="/inventaris" class="nav-link <?= str_starts_with($curUri,'/inventaris')?'active':'' ?>"><i class="bi bi-tools"></i> Inventaris Alat</a>
    <a href="/supplier"   class="nav-link <?= str_starts_with($curUri,'/supplier')?'active':'' ?>"><i class="bi bi-truck"></i> Data Supplier</a>

    <div class="nav-label">Transaksi</div>
    <a href="/pesanan"    class="nav-link <?= str_starts_with($curUri,'/pesanan')?'active':'' ?>"><i class="bi bi-receipt"></i> Pesanan</a>
    <a href="/pembayaran" class="nav-link <?= str_starts_with($curUri,'/pembayaran')?'active':'' ?>"><i class="bi bi-credit-card"></i> Pembayaran</a>

    <div class="nav-label">Pelanggan</div>
    <a href="/admin/ulasan" class="nav-link <?= str_starts_with($curUri,'/admin/ulasan')?'active':'' ?>"><i class="bi bi-star"></i> Ulasan & Rating</a>
    <a href="/admin/rekap" class="nav-link <?= str_starts_with($curUri,'/admin/rekap')?'active':'' ?>"><i class="bi bi-bar-chart-line"></i> Rekap Penjualan</a>
    <a href="/admin/users"  class="nav-link <?= str_starts_with($curUri,'/admin/users')?'active':'' ?>"><i class="bi bi-person-gear"></i> Manajemen Akun</a>

    <div class="nav-label">Akun</div>
    <a href="/logout" class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
</nav>

<div id="main">
    <div id="topbar">
        <div style="font-weight:700;color:#1a1f36;font-size:.95rem"><i class="bi bi-grid-fill me-2" style="color:var(--accent)"></i><?= e($pageTitle ?? 'Dashboard') ?></div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:.8rem"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y') ?></span>
            <?php if ($u): ?>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;background:var(--accent);font-size:.8rem"><?= strtoupper(substr($u['nama'],0,1)) ?></div>
                <span style="font-size:.82rem;font-weight:600"><?= e($u['nama']) ?></span>
            </div>
            <?php endif; ?>
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
