<?php
$page_title = "Informasi Keuangan";
include_once('includes/header_frontend.php');
$transaksi = $conn->query("SELECT * FROM raka_keuangan ORDER BY tanggal_transaksi DESC LIMIT 50"); // Batasi 50 transaksi terakhir
$saldo_akhir = $conn->query("SELECT (SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pemasukan') - (SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pengeluaran')")->fetch_row()[0] ?? 0;
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-center bg-white py-3">
            <h3>Laporan Keuangan Masjid</h3>
            <p class="mb-0">Transparansi dana untuk kemakmuran bersama.</p>
        </div>
        <div class="card-body">
            <div class="alert alert-success text-center">
                <strong>Total Saldo Kas Saat Ini: Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></strong>
            </div>
            <h5 class="mt-4">50 Riwayat Transaksi Terakhir:</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead><tr><th>Tanggal</th><th>Kategori</th><th>Pemasukan</th><th>Pengeluaran</th></tr></thead>
                    <tbody>
                    <?php while ($row = $transaksi->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($row['tanggal_transaksi'])) ?></td>
                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                        <td class="text-success"><?= $row['jenis_transaksi'] == 'Pemasukan' ? 'Rp '.number_format($row['jumlah'], 0, ',', '.') : '-' ?></td>
                        <td class="text-danger"><?= $row['jenis_transaksi'] == 'Pengeluaran' ? 'Rp '.number_format($row['jumlah'], 0, ',', '.') : '-' ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>