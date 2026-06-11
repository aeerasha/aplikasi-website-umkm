<?php
class PegawaiController {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); requireRole('admin'); }

    public function index(): void {
        $search = $_GET['search'] ?? '';
        if ($search) {
            $stmt = $this->db->prepare("SELECT * FROM pegawai WHERE nama LIKE ? OR jabatan LIKE ? ORDER BY id DESC");
            $stmt->execute(["%$search%", "%$search%"]);
        } else {
            $stmt = $this->db->query("SELECT * FROM pegawai ORDER BY id DESC");
        }
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
        $this->db->prepare("DELETE FROM pegawai WHERE id=?")->execute([$id]);
        flash('success', 'Pegawai berhasil dihapus!');
        redirect('/pegawai');
    }
}
