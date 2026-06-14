<?php
class PegawaiController {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); requireRole('admin'); }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        $params = [];

        // JOIN ke users agar kolom role dan status aktif/nonaktif akun ikut terbaca
        $sql = "
            SELECT
                pg.*,
                u.role   AS akun_role,
                u.aktif  AS akun_aktif,
                u.email  AS akun_email
            FROM pegawai pg
            LEFT JOIN users u ON u.id = pg.user_id
            WHERE 1=1
        ";

        if ($search) {
            $sql   .= " AND (pg.nama LIKE ? OR pg.jabatan LIKE ? OR pg.email LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $sql .= " ORDER BY pg.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pegawais = $stmt->fetchAll();

        renderView('pegawai/index', compact('pegawais', 'search'));
    }

    public function create(): void { renderView('pegawai/create', []); }

    public function store(): void {
        $data = [
            'nama'     => trim($_POST['nama'] ?? ''),
            'jabatan'  => trim($_POST['jabatan'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'telepon'  => trim($_POST['telepon'] ?? ''),
            'alamat'   => trim($_POST['alamat'] ?? ''),
            'gaji'     => (float)($_POST['gaji'] ?? 0),
        ];

        // Validasi Nama
        if (empty($data['nama'])) { flash('error', 'Nama pegawai wajib diisi.'); redirect('/pegawai/create'); return; }
        
        // Validasi Telepon: Harus angka saja
        if (!preg_match('/^[0-9]+$/', $data['telepon'])) {
            flash('error', 'Nomor telepon harus berupa angka!');
            redirect('/pegawai/create');
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO pegawai (nama, jabatan, email, telepon, alamat, gaji) VALUES (?,?,?,?,?,?)");
        $stmt->execute(array_values($data));
        flash('success', 'Pegawai berhasil ditambahkan!');
        redirect('/pegawai');
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM pegawai WHERE id=?");
        $stmt->execute([$id]);
        $pegawai = $stmt->fetchObject();
        if (!$pegawai) { flash('error', 'Pegawai tidak ditemukan.'); redirect('/pegawai'); }
        renderView('pegawai/edit', compact('pegawai'));
    }

    public function update(): void {
        $id   = (int)($_POST['id'] ?? 0);
        $data = [
            'nama'    => trim($_POST['nama'] ?? ''),
            'jabatan' => trim($_POST['jabatan'] ?? ''),
            'email'   => trim($_POST['email'] ?? ''),
            'telepon' => trim($_POST['telepon'] ?? ''),
            'alamat'  => trim($_POST['alamat'] ?? ''),
            'gaji'    => (float)($_POST['gaji'] ?? 0),
        ];

        // Validasi Nama
        if (empty($data['nama'])) { flash('error', 'Nama pegawai wajib diisi.'); redirect("/pegawai/edit?id=$id"); return; }
        
        // Validasi Telepon: Harus angka saja
        if (!preg_match('/^[0-9]+$/', $data['telepon'])) {
            flash('error', 'Nomor telepon harus berupa angka!');
            redirect("/pegawai/edit?id=$id");
            return;
        }

        $stmt = $this->db->prepare("UPDATE pegawai SET nama=?, jabatan=?, email=?, telepon=?, alamat=?, gaji=? WHERE id=?");
        $stmt->execute([...array_values($data), $id]);
        flash('success', 'Data pegawai berhasil diperbarui!');
        redirect('/pegawai');
    }

    public function destroy(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            flash('error', 'ID tidak valid.');
            redirect('/pegawai');
        }

        // Ambil user_id yang tertaut sebelum dihapus
        $stmt = $this->db->prepare("SELECT user_id FROM pegawai WHERE id = ?");
        $stmt->execute([$id]);
        $pegawai = $stmt->fetchObject();

        if (!$pegawai) {
            flash('error', 'Data pegawai tidak ditemukan.');
            redirect('/pegawai');
        }

        $this->db->beginTransaction();
        try {
            $this->db->prepare("DELETE FROM pegawai WHERE id = ?")->execute([$id]);

            // Hapus akun users yang tertaut, hanya jika role-nya 'pegawai' (jangan sampai hapus admin)
            if ($pegawai->user_id) {
                $this->db->prepare(
                    "DELETE FROM users WHERE id = ? AND role = 'pegawai'"
                )->execute([$pegawai->user_id]);
            }

            $this->db->commit();
            flash('success', 'Pegawai dan akun terkait berhasil dihapus.');
        } catch (\Exception $e) {
            $this->db->rollBack();
            flash('error', 'Gagal menghapus data pegawai. Coba lagi.');
        }

        redirect('/pegawai');
    }
}
