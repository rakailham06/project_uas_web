<?php
$page_title = "Kelola Data Kurban";
include_once('../includes/header_admin.php');
redirect_if_not_logged_in();
if (!is_admin()) { exit("Akses Ditolak"); }

echo '<a href="dashboard.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>';
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM raka_kurban WHERE id_kurban = $id");
    $edit_data = $result->fetch_assoc();
}

// Proses Tambah / Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_SESSION['id_pengurus'])) {
        exit("Error: Anda tidak terdaftar sebagai pengurus dan tidak bisa mencatat data.");
    }

    $id_kurban = $_POST['id_kurban'] ?? null;
    $id_pengurus_pencatat = $_SESSION['id_pengurus'];
    $nama_pekurban = $_POST['nama_pekurban'];
    $jenis_hewan = $_POST['jenis_hewan'];
    $tahun_hijriah = $_POST['tahun_hijriah'];
    $status_pembayaran = $_POST['status_pembayaran'];

    if ($id_kurban) { 
        $stmt = $conn->prepare("UPDATE raka_kurban SET nama_pekurban=?, jenis_hewan=?, tahun_hijriah=?, status_pembayaran=? WHERE id_kurban=?");
        $stmt->bind_param("ssisi", $nama_pekurban, $jenis_hewan, $tahun_hijriah, $status_pembayaran, $id_kurban);
    } else {
        $stmt = $conn->prepare("INSERT INTO raka_kurban (id_pengurus_pencatat, nama_pekurban, jenis_hewan, tahun_hijriah, status_pembayaran) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiss", $id_pengurus_pencatat, $nama_pekurban, $jenis_hewan, $tahun_hijriah, $status_pembayaran);
    }
    $stmt->execute();
    header("Location: kelola_kurban.php");
    exit;
}

// Proses Hapus
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM raka_kurban WHERE id_kurban = $id");
    header("Location: kelola_kurban.php");
    exit;
}

$kurban = $conn->query("SELECT k.*, p.nama_lengkap as pencatat FROM raka_kurban k LEFT JOIN raka_pengurus p ON k.id_pengurus_pencatat = p.id_pengurus ORDER BY k.tahun_hijriah DESC, k.nama_pekurban ASC");
?>

<div class="card mb-4">
    <div class="card-header">
        <h4><i class="bi bi-person-plus-fill"></i> <?= $edit_data ? 'Edit Data' : 'Daftarkan Peserta' ?> Kurban</h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id_kurban" value="<?= $edit_data['id_kurban'] ?? '' ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Pekurban</label>
                    <input class="form-control" name="nama_pekurban" placeholder="Nama Lengkap Pekurban" value="<?= $edit_data['nama_pekurban'] ?? '' ?>" required>
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Tahun Hijriah</label>
                    <input class="form-control" name="tahun_hijriah" type="number" placeholder="Contoh: 1447" value="<?= $edit_data['tahun_hijriah'] ?? '' ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Hewan</label>
                    <select class="form-select" name="jenis_hewan" required>
                        <option value="Kambing" <?= ($edit_data['jenis_hewan'] ?? '') == 'Kambing' ? 'selected' : '' ?>>Kambing/Domba</option>
                        <option value="Sapi" <?= ($edit_data['jenis_hewan'] ?? '') == 'Sapi' ? 'selected' : '' ?>>Sapi (Per Orang)</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Status Pembayaran</label>
                    <select class="form-select" name="status_pembayaran" required>
                        <option value="Lunas" <?= ($edit_data['status_pembayaran'] ?? '') == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                        <option value="Belum Lunas" <?= ($edit_data['status_pembayaran'] ?? '') == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> <?= $edit_data ? 'Update Data' : 'Simpan Data' ?></button>
                    <?php if ($edit_data): ?>
                        <a href="kelola_kurban.php" class="btn btn-secondary">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4><i class="bi bi-list-ol"></i> Daftar Peserta Kurban</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr><th>Tahun</th><th>Nama Pekurban</th><th>Hewan</th><th>Status</th><th>Pencatat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php while ($row = $kurban->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['tahun_hijriah'] ?> H</td>
                    <td><?= htmlspecialchars($row['nama_pekurban']) ?></td>
                    <td><?= htmlspecialchars($row['jenis_hewan']) ?></td>
                    <td><span class="badge bg-<?= $row['status_pembayaran']=='Lunas'?'success':'warning text-dark' ?>"><?= $row['status_pembayaran'] ?></span></td>
                    <td><?= htmlspecialchars($row['pencatat'] ?? 'N/A') ?></td>
                    <td>
                        <a href="?edit=<?= $row['id_kurban'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <a href="?delete=<?= $row['id_kurban'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>