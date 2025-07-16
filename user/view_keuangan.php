<?php
$page_title = "Laporan Keuangan";
include_once('../includes/header_user.php');
redirect_if_not_logged_in();

$transaksi = $conn->query("SELECT * FROM raka_keuangan ORDER BY tanggal_transaksi DESC");
?>
<div class="card">
    <div class="card-header"><h4>Riwayat Transaksi Keuangan Masjid</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead><tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Pemasukan</th><th>Pengeluaran</th></tr></thead>
                <tbody>
                <?php while ($row = $transaksi->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($row['tanggal_transaksi'])) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td class="text-success"><?= $row['jenis_transaksi'] == 'Pemasukan' ? 'Rp '.number_format($row['jumlah'], 0, ',', '.') : '-' ?></td>
                    <td class="text-danger"><?= $row['jenis_transaksi'] == 'Pengeluaran' ? 'Rp '.number_format($row['jumlah'], 0, ',', '.') : '-' ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once('../includes/footer.php'); ?>