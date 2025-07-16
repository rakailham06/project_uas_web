<?php
$page_title = "Data Kurban";
include_once('../includes/header_user.php');
redirect_if_not_logged_in();

// Ambil tahun yang dipilih dari URL
$tahun_filter = $_GET['tahun'] ?? '';

// Bangun query SQL dengan kondisi WHERE yang benar
$sql = "SELECT k.* FROM raka_kurban k";
if (!empty($tahun_filter) && is_numeric($tahun_filter)) {
    // Hanya tambahkan klausa WHERE jika tahun_filter tidak kosong dan merupakan angka
    $sql .= " WHERE k.tahun_hijriah = " . intval($tahun_filter);
}
$sql .= " ORDER BY k.tahun_hijriah DESC, k.nama_pekurban ASC";

$kurban = $conn->query($sql);

// Query untuk mengisi dropdown filter tahun
$daftar_tahun = $conn->query("SELECT DISTINCT tahun_hijriah FROM raka_kurban ORDER BY tahun_hijriah DESC");

?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="bi bi-clipboard-heart-fill"></i> Data Peserta Kurban</h4>
        <form method="GET" class="d-flex gap-2">
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Tampilkan Semua Tahun --</option>
                <?php while($th = $daftar_tahun->fetch_assoc()): ?>
                    <option value="<?= $th['tahun_hijriah'] ?>" <?= ($tahun_filter == $th['tahun_hijriah']) ? 'selected' : '' ?>>
                        <?= $th['tahun_hijriah'] ?> H
                    </option>
                <?php endwhile; ?>
            </select>
            <a href="view_kurban.php" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tahun Hijriah</th>
                        <th>Nama Pekurban</th>
                        <th>Jenis Hewan</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($kurban && $kurban->num_rows > 0):
                        while ($row = $kurban->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['tahun_hijriah'] ?> H</td>
                        <td><?= htmlspecialchars($row['nama_pekurban']) ?></td>
                        <td><?= htmlspecialchars($row['jenis_hewan']) ?></td>
                        <td>
                            <span class="badge bg-<?= $row['status_pembayaran']=='Lunas' ? 'success' : 'warning text-dark' ?>">
                                <?= $row['status_pembayaran'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once('../includes/footer.php'); ?>