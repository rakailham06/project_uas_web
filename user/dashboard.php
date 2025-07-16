<?php
$page_title = "Dashboard";
include_once('../includes/header_user.php');
redirect_if_not_logged_in();
if (is_admin()) { header('Location: ../admin/dashboard.php'); exit; }

$kas_masuk = $conn->query("SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pemasukan'")->fetch_row()[0] ?? 0;
$kas_keluar = $conn->query("SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pengeluaran'")->fetch_row()[0] ?? 0;
$saldo_akhir = $kas_masuk - $kas_keluar;
?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h3>Selamat Datang, Jamaah <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>!</h3>
        <p class="text-muted mb-0">Silakan pilih menu di bawah untuk melihat informasi seputar kegiatan dan data masjid.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <a href="view_keuangan.php" class="text-decoration-none text-dark">
            <div class="card card-body text-center h-100 shadow-sm">
                <i class="bi bi-cash-stack fs-1 text-success"></i>
                <h5 class="card-title mt-2">Laporan Keuangan</h5>
                <p class="mb-0">Lihat rincian kas masuk dan keluar.</p>
                <p class="fw-bold fs-5 mt-2">Saldo: Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></p>
            </div>
        </a>
    </div>
     <div class="col-md-6">
        <a href="view_inventaris.php" class="text-decoration-none text-dark">
            <div class="card card-body text-center h-100 shadow-sm">
                <i class="bi bi-box-seam fs-1 text-primary"></i>
                <h5 class="card-title mt-2">Data Inventaris</h5>
                <p class="mb-0">Lihat daftar aset dan barang milik masjid.</p>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="view_kurban.php" class="text-decoration-none text-dark">
            <div class="card card-body text-center h-100 shadow-sm">
                <i class="bi bi-clipboard-heart fs-1 text-warning"></i>
                <h5 class="card-title mt-2">Info Kurban</h5>
                <p class="mb-0">Lihat daftar peserta kurban dari tahun ke tahun.</p>
            </div>
        </a>
    </div>
     <div class="col-md-6">
        <a href="view_pengurus.php" class="text-decoration-none text-dark">
            <div class="card card-body text-center h-100 shadow-sm">
                <i class="bi bi-people-fill fs-1 text-info"></i>
                <h5 class="card-title mt-2">Daftar Pengurus</h5>
                <p class="mb-0">Lihat struktur kepengurusan dan user terdaftar.</p>
            </div>
        </a>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>