<?php
$page_title = "Kelola Keuangan";
include_once('../includes/header_admin.php');
redirect_if_not_logged_in();

echo '<a href="dashboard.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>';
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM keuangan WHERE id_transaksi = $id");
    $edit_data = $result->fetch_assoc();
}

// Proses Tambah / Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'] ?? null;
    $id_pengurus_pencatat = $_SESSION['id_pengurus'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $keterangan = $_POST['keterangan'];
    $kategori = $_POST['kategori'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $jumlah = $_POST['jumlah'];

    if ($id_transaksi) { // Update
        $stmt = $conn->prepare("UPDATE raka_keuangan SET tanggal_transaksi=?, keterangan=?, kategori=?, jenis_transaksi=?, jumlah=? WHERE id_transaksi=?");
        $stmt->bind_param("ssssdi", $tanggal_transaksi, $keterangan, $kategori, $jenis_transaksi, $jumlah, $id_transaksi);
    } else { // Insert
        $stmt = $conn->prepare("INSERT INTO raka_keuangan (id_pengurus_pencatat, tanggal_transaksi, keterangan, kategori, jenis_transaksi, jumlah) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssd", $id_pengurus_pencatat, $tanggal_transaksi, $keterangan, $kategori, $jenis_transaksi, $jumlah);
    }
    $stmt->execute();
    header("Location: kelola_keuangan.php");
    exit;
}

// Proses Hapus
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM keuangan WHERE id_transaksi = $id");
    header("Location: kelola_keuangan.php");
    exit;
}

$transaksi = $conn->query("SELECT k.*, p.nama_lengkap as pencatat FROM raka_keuangan k JOIN raka_pengurus p ON k.id_pengurus_pencatat = p.id_pengurus ORDER BY k.tanggal_transaksi DESC");

// Hitung total
$total_pemasukan = $conn->query("SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pemasukan'")->fetch_row()[0] ?? 0;
$total_pengeluaran = $conn->query("SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pengeluaran'")->fetch_row()[0] ?? 0;
$saldo_akhir = $total_pemasukan - $total_pengeluaran;
?>

<div class="card mb-4">
    <div class="card-header">
        <h4><i class="bi bi-pencil-square"></i> <?= $edit_data ? 'Edit' : 'Catat' ?> Transaksi Keuangan</h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id_transaksi" value="<?= $edit_data['id_transaksi'] ?? '' ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Transaksi</label>
                    <input class="form-control" name="tanggal_transaksi" type="date" value="<?= $edit_data['tanggal_transaksi'] ?? date('Y-m-d') ?>" required>
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input class="form-control" name="jumlah" type="number" step="100" placeholder="Contoh: 50000" value="<?= $edit_data['jumlah'] ?? '' ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Transaksi</label>
                    <select class="form-select" name="jenis_transaksi" required>
                        <option value="Pemasukan" <?= ($edit_data['jenis_transaksi'] ?? '') == 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="Pengeluaran" <?= ($edit_data['jenis_transaksi'] ?? '') == 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <input class="form-control" name="kategori" placeholder="Cth: Infaq Jumat, Zakat Fitrah, Biaya Listrik" value="<?= $edit_data['kategori'] ?? '' ?>" required>
                </div>
                <div class="col-md-12">
                     <label class="form-label">Keterangan</label>
                    <textarea class="form-control" name="keterangan" placeholder="Keterangan tambahan (opsional)"><?= $edit_data['keterangan'] ?? '' ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> <?= $edit_data ? 'Update Transaksi' : 'Simpan Transaksi' ?></button>
                    <?php if ($edit_data): ?>
                        <a href="kelola_keuangan.php" class="btn btn-secondary">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4><i class="bi bi-table"></i> Riwayat Transaksi</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th>Pemasukan (Rp)</th><th>Pengeluaran (Rp)</th><th>Pencatat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php while ($row = $transaksi->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($row['tanggal_transaksi'])) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td class="text-end text-success fw-bold"><?= $row['jenis_transaksi'] == 'Pemasukan' ? number_format($row['jumlah'], 0, ',', '.') : '-' ?></td>
                    <td class="text-end text-danger fw-bold"><?= $row['jenis_transaksi'] == 'Pengeluaran' ? number_format($row['jumlah'], 0, ',', '.') : '-' ?></td>
                    <td><?= htmlspecialchars($row['pencatat']) ?></td>
                    <td>
                        <a href="?edit=<?= $row['id_transaksi'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <a href="?delete=<?= $row['id_transaksi'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
                <tfoot class="fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Total</td>
                        <td class="text-end text-success"><?= number_format($total_pemasukan, 0, ',', '.') ?></td>
                        <td class="text-end text-danger"><?= number_format($total_pengeluaran, 0, ',', '.') ?></td>
                        <td colspan="2"></td>
                    </tr>
                     <tr>
                        <td colspan="3" class="text-end">SALDO AKHIR</td>
                        <td colspan="2" class="text-center fs-5"><?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>