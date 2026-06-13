<?php
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function e(mixed $val): string {
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8');
}

function flash(string $key, string $msg): void {
    $_SESSION['flash'][$key] = $msg;
}

function getFlash(string $key): ?string {
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function formatRupiah(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function renderView(string $view, array $data = []): void {
    extract($data);
    $content = BASE_PATH . "/resources/views/$view.php";
    include BASE_PATH . '/resources/views/layouts/app.php';
}

function renderCustomer(string $view, array $data = []): void {
    extract($data);
    $content = BASE_PATH . "/resources/views/$view.php";
    include BASE_PATH . '/resources/views/layouts/customer.php';
}

function renderPegawai(string $view, array $data = []): void {
    extract($data);
    $content = BASE_PATH . "/resources/views/$view.php";
    include BASE_PATH . '/resources/views/layouts/pegawai.php';
}

function statusBadge(string $status): string {
    $map = [
        'pending'   => 'warning',
        'diproses'  => 'info',
        'dikirim'   => 'primary',
        'selesai'   => 'success',
        'batal'     => 'danger',
        'lunas'     => 'success',
        'gagal'     => 'danger',
    ];
    $color = $map[$status] ?? 'secondary';
    return "<span class=\"badge bg-$color\">" . ucfirst(e($status)) . "</span>";
}

function starRating(int $rating): string {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return "<span class=\"text-warning\">$stars</span>";
}

// ── Auth helpers ─────────────────────────────────────────────────────────────

function auth(): ?array {
    return $_SESSION['user'] ?? null;
}

function isAdmin(): bool {
    return ($_SESSION['user']['role'] ?? '') === 'admin';
}

function isPegawai(): bool {
    return in_array($_SESSION['user']['role'] ?? '', ['pegawai','admin']);
}

function isCustomer(): bool {
    return ($_SESSION['user']['role'] ?? '') === 'pelanggan';
}

function requireAuth(): void {
    if (!isset($_SESSION['user'])) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('/login');
    }
}

function requireRole(string ...$roles): void {
    requireAuth();
    $userRole = $_SESSION['user']['role'] ?? '';
    if (!in_array($userRole, $roles)) {
        // Redirect ke dashboard sesuai role
        match ($userRole) {
            'admin'     => redirect('/admin/dashboard'),
            'pegawai'   => redirect('/pegawai/dashboard'),
            default     => redirect('/customer/dashboard'),
        };
    }
}

// ── Guest Customer (tanpa login) ─────────────────────────────────────────────

/**
 * Ambil data sesi tamu customer (nama, no_hp, nomor_meja).
 */
function customerSession(): ?array {
    return $_SESSION['customer'] ?? null;
}

/**
 * Proteksi halaman customer: cukup cek $_SESSION['customer'],
 * tidak perlu akun login. Jika belum ada, redirect ke form identitas.
 */
function requireCustomer(): void {
    if (empty($_SESSION['customer']['nama'])
        || empty($_SESSION['customer']['no_hp'])
        || empty($_SESSION['customer']['nomor_meja'])) {
        flash('error', 'Silakan isi identitas Anda terlebih dahulu.');
        redirect('/customer/identitas');
    }
}
