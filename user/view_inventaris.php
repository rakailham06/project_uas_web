<?php
$page_title = "Data Inventaris";
include_once('../includes/header_user.php');
redirect_if_not_logged_in();

echo '<a href="dashboard.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>';
$inventaris = $conn->query("SELECT * FROM raka_inventaris ORDER BY nama_barang ASC");
?>
<div class="card">
    <div class="card-header"><h4>Data Inventaris Masjid</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead><tr><th>Nama Barang</th><th>Jumlah</th><th>Kondisi</th><th>Lokasi Penyimpanan</th></tr></thead>
                <tbody>
                <?php while ($row = $inventaris->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah']) ?></td>
                    <td><?= htmlspecialchars($row['kondisi']) ?></td>
                    <td><?= htmlspecialchars($row['lokasi_penyimpanan']) ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once('../includes/footer.php'); ?>