<?php
class AuthController {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function showLogin(): void {
        if (isset($_SESSION['user'])) redirect('/');
        include BASE_PATH . '/resources/views/auth/login.php';
    }

    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            $_SESSION['auth_error'] = 'Email dan password wajib diisi.';
            redirect('/login');
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=? AND aktif=1");
        $stmt->execute([$email]);
        $user = $stmt->fetchObject();

        if (!$user || !password_verify($password, $user->password)) {
            $_SESSION['auth_error'] = 'Email atau password salah.';
            redirect('/login');
        }

        $_SESSION['user'] = [
            'id'    => $user->id,
            'nama'  => $user->nama,
            'email' => $user->email,
            'role'  => $user->role,
        ];
        unset($_SESSION['auth_error']);

        // Redirect per role
        match ($user->role) {
            'admin'     => redirect('/admin/dashboard'),
            'pegawai'   => redirect('/pegawai/dashboard'),
            default     => redirect('/customer/dashboard'),
        };
    }

    public function showRegister(): void {
        if (isset($_SESSION['user'])) redirect('/');
        include BASE_PATH . '/resources/views/auth/register.php';
    }

    public function register(): void {
        $nama     = trim($_POST['nama'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $telepon  = trim($_POST['telepon'] ?? '');
        $alamat   = trim($_POST['alamat'] ?? '');

        if (!$nama || !$email || !$password) {
            $_SESSION['reg_error'] = 'Nama, email, dan password wajib diisi.';
            redirect('/register');
        }

        // Cek email duplikat
        $cek = $this->db->prepare("SELECT id FROM users WHERE email=?");
        $cek->execute([$email]);
        if ($cek->fetchObject()) {
            $_SESSION['reg_error'] = 'Email sudah terdaftar.';
            redirect('/register');
        }

        $stmt = $this->db->prepare("INSERT INTO users (nama, email, password, role, telepon, alamat) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nama, $email, password_hash($password, PASSWORD_DEFAULT), 'pelanggan', $telepon, $alamat]);

        $_SESSION['auth_success'] = 'Registrasi berhasil! Silakan login.';
        redirect('/login');
    }

    public function logout(): void {
        session_destroy();
        redirect('/login');
    }
}
