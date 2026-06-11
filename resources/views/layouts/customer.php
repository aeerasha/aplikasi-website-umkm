<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($pageTitle ?? 'Customer') ?> — UMKM App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --accent:#10b981; --accent2:#059669; }
        body { font-family:'Segoe UI',sans-serif; background:#f0fdf4; min-height:100vh; }
        .topnav { background:#fff; border-bottom:2px solid #d1fae5; padding:0 20px; height:58px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:100; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .topnav .brand { font-weight:800; color:#1a1f36; font-size:1rem; display:flex; align-items:center; gap:8px; text-decoration:none; }
        .topnav .brand span { background:linear-gradient(135deg,#10b981,#059669); color:#fff; border-radius:8px; padding:2px 10px; font-size:.82rem; }
        .nav-link-top { color:#374151; font-size:.85rem; font-weight:500; padding:5px 10px; border-radius:8px; text-decoration:none; transition:all .2s; display:flex; align-items:center; gap:5px; white-space:nowrap; }
        .nav-link-top:hover, .nav-link-top.active { background:#d1fae5; color:#059669; }
        .content-area { padding:24px 20px; max-width:1200px; margin:0 auto; }
        .card { border:none; border-radius:14px; box-shadow:0 2px 14px rgba(0,0,0,.06); }
        .card-header { background:#fff; border-bottom:1px solid #ecfdf5; border-radius:14px 14px 0 0!important; padding:14px 18px; font-weight:700; }
        .stat-card { border:none; border-radius:14px; padding:18px; color:#fff; }
        .stat-card .num { font-size:1.6rem; font-weight:800; }
        .stat-card .lbl { font-size:.78rem; opacity:.9; }
        .table th { font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b; font-weight:700; }
        .table td { vertical-align:middle; font-size:.87rem; }
        .badge { border-radius:7px; font-weight:600; font-size:.73rem; padding:4px 9px; }
        .form-control,.form-select { border-radius:9px; border:1.5px solid #d1fae5; padding:9px 13px; }
        .form-control:focus,.form-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(16,185,129,.13); }
        .btn-success { background:var(--accent); border-color:var(--accent); border-radius:9px; font-weight:600; }
        .btn-success:hover { background:var(--accent2); border-color:var(--accent2); }
        .btn-outline-success { border-color:var(--accent); color:var(--accent); border-radius:9px; }
        .btn-outline-secondary { border-radius:9px; }
        .product-card { border:none; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); transition:transform .2s,box-shadow .2s; }
        .product-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(16,185,129,.15); }
        .fw-800 { font-weight:800!important; }
    </style>
</head>
<body>
<?php $curUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); $u = auth(); ?>
<nav class="topnav">
    <a href="/customer/dashboard" class="brand">
        <i class="bi bi-shop" style="color:#10b981"></i>UMKM <span>Pelanggan</span>
    </a>
    <div class="d-flex align-items-center gap-1 overflow-auto">
        <a href="/customer/dashboard"    class="nav-link-top <?= $curUri==='/customer/dashboard'?'active':'' ?>"><i class="bi bi-house"></i><span class="d-none d-md-inline">Beranda</span></a>
        <a href="/customer/katalog"      class="nav-link-top <?= $curUri==='/customer/katalog'?'active':'' ?>"><i class="bi bi-grid"></i><span class="d-none d-md-inline">Katalog</span></a>
        <a href="/customer/pesanan-saya" class="nav-link-top <?= str_starts_with($curUri,'/customer/pesanan')?'active':'' ?>"><i class="bi bi-receipt"></i><span class="d-none d-md-inline">Pesanan</span></a>
        <a href="/customer/riwayat-bayar" class="nav-link-top <?= str_starts_with($curUri,'/customer/riwayat')?'active':'' ?>"><i class="bi bi-credit-card"></i><span class="d-none d-md-inline">Pembayaran</span></a>
        <a href="/customer/ulasan" class="nav-link-top <?= str_starts_with($curUri, '/customer/ulasan') ? 'active' : '' ?>">
            <i class="bi bi-star"></i><span class="d-none d-md-inline">Ulasan</span>
        </a>
        <a href="/logout" class="nav-link-top text-danger"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</nav>
<div class="content-area">
    <?php if ($f=getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 mb-3" style="border-radius:10px"><i class="bi bi-check-circle-fill me-2"></i><?= e($f) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($f=getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3" style="border-radius:10px"><i class="bi bi-exclamation-circle-fill me-2"></i><?= e($f) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php include $content; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
