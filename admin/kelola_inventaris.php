<?php
$page_title = "Kelola Inventaris";
include_once('../includes/header_admin.php');
redirect_if_not_logged_in();
if (!is_admin()) { exit("Akses Ditolak"); }

echo '<a href="dashboard.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>';

$edit_data = null;
$is_edit_mode = false;

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id_to_edit = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM raka_inventaris WHERE id_inventaris = $id_to_edit");
    if ($result && $result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
        $is_edit_mode = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_SESSION['id_pengurus'])) {
        exit('<div class="alert alert-danger">Error: Akun admin Anda tidak memiliki profil pengurus yang terhubung untuk mencatat data.</div>');
    }

    $id_inventaris = $_POST['id_inventaris'] ?? null;
    $id_pengurus_pencatat = $_SESSION['id_pengurus'];
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $satuan = $_POST['satuan'];
    $kondisi = $_POST['kondisi'];
    $tanggal_perolehan = $_POST['tanggal_perolehan'];
    $lokasi_penyimpanan = $_POST['lokasi_penyimpanan'];

    if (!empty($id_inventaris)) { // Mode Update
        $stmt = $conn->prepare("UPDATE inventaris SET kode_barang=?, nama_barang=?, jumlah=?, satuan=?, kondisi=?, tanggal_perolehan=?, lokasi_penyimpanan=? WHERE id_inventaris=?");
        $stmt->bind_param("ssissssi", $kode_barang, $nama_barang, $jumlah, $satuan, $kondisi, $tanggal_perolehan, $lokasi_penyimpanan, $id_inventaris);
    } else { // Mode Insert
        $stmt = $conn->prepare("INSERT INTO inventaris (id_pengurus_pencatat, kode_barang, nama_barang, jumlah, satuan, kondisi, tanggal_perolehan, lokasi_penyimpanan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssisss", $id_pengurus_pencatat, $kode_barang, $nama_barang, $jumlah, $satuan, $kondisi, $tanggal_perolehan, $lokasi_penyimpanan);
    }
    
    if ($stmt->execute()) {
        header("Location: kelola_inventaris.php?status=sukses");
    } else {
        header("Location: kelola_inventaris.php?status=gagal");
    }
    exit;
}

// Proses Hapus
if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    $conn->query("DELETE FROM raka_inventaris WHERE id_inventaris = $id_to_delete");
    header("Location: kelola_inventaris.php?status=dihapus");
    exit;
}

// Ambil semua data inventaris untuk ditampilkan di tabel
$inventaris_list = $conn->query("SELECT i.*, p.nama_lengkap as pencatat FROM raka_inventaris i LEFT JOIN raka_pengurus p ON i.id_pengurus_pencatat = p.id_pengurus ORDER BY i.nama_barang ASC");
?>

<div class="card mb-4">
    <div class="card-header">
        <h4><i class="bi bi-pencil-square"></i> <?= $is_edit_mode ? 'Edit Data Inventaris' : 'Tambah Inventaris Baru' ?></h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id_inventaris" value="<?= $is_edit_mode ? $edit_data['id_inventaris'] : '' ?>">
            
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Barang</label>
                    <input class="form-control" name="nama_barang" placeholder="Contoh: Speaker TOA, Karpet Masjid" value="<?= $is_edit_mode ? htmlspecialchars($edit_data['nama_barang']) : '' ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kode Barang</label>
                    <input class="form-control" name="kode_barang" placeholder="(Opsional)" value="<?= $is_edit_mode ? htmlspecialchars($edit_data['kode_barang']) : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah</label>
                    <input class="form-control" name="jumlah" type="number" placeholder="Jumlah barang" value="<?= $is_edit_mode ? $edit_data['jumlah'] : '' ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Satuan</label>
                    <input class="form-control" name="satuan" placeholder="Contoh: Buah, Unit, Meter, Gulung" value="<?= $is_edit_mode ? htmlspecialchars($edit_data['satuan']) : '' ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kondisi Barang</label>
                    <select class="form-select" name="kondisi" required>
                        <option value="Baik" <?= ($is_edit_mode && $edit_data['kondisi'] == 'Baik') ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= ($is_edit_mode && $edit_data['kondisi'] == 'Rusak Ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= ($is_edit_mode && $edit_data['kondisi'] == 'Rusak Berat') ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Perolehan</label>
                    <input class="form-control" name="tanggal_perolehan" type="date" value="<?= $is_edit_mode ? $edit_data['tanggal_perolehan'] : '' ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Lokasi Penyimpanan</label>
                    <input class="form-control" name="lokasi_penyimpanan" placeholder="Contoh: Gudang, Ruang Audio" value="<?= $is_edit_mode ? htmlspecialchars($edit_data['lokasi_penyimpanan']) : '' ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> <?= $is_edit_mode ? 'Update Data' : 'Simpan Data' ?></button>
                    <?php if ($is_edit_mode): ?>
                        <a href="kelola_inventaris.php" class="btn btn-secondary">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4><i class="bi bi-list-task"></i> Daftar Inventaris</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr><th>Nama Barang</th><th>Jumlah</th><th>Kondisi</th><th>Tgl Perolehan</th><th>Pencatat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php while ($row = $inventaris_list->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= $row['jumlah'] . ' ' . htmlspecialchars($row['satuan']) ?></td>
                    <td><span class="badge bg-<?= $row['kondisi']=='Baik'?'success':'danger' ?>"><?= $row['kondisi'] ?></span></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_perolehan'])) ?></td>
                    <td><?= htmlspecialchars($row['pencatat'] ?? 'N/A') ?></td>
                    <td>
                        <a href="?edit=<?= $row['id_inventaris'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                        <a href="?delete=<?= $row['id_inventaris'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>