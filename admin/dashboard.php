<?php
$page_title = "Dashboard";
include_once('../includes/header_admin.php');
redirect_if_not_logged_in();

$jumlah_pengurus = $conn->query("SELECT COUNT(*) FROM raka_pengurus")->fetch_row()[0];
$total_inventaris = $conn->query("SELECT SUM(jumlah) FROM raka_inventaris")->fetch_row()[0] ?? 0;
$kas_masuk = $conn->query("SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pemasukan'")->fetch_row()[0] ?? 0;
$kas_keluar = $conn->query("SELECT SUM(jumlah) FROM raka_keuangan WHERE jenis_transaksi = 'Pengeluaran'")->fetch_row()[0] ?? 0;
$saldo_akhir = $kas_masuk - $kas_keluar;
?>

<div class="row g-4">
    <div class="col-md-6 col-lg-3">
        <div class="card bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Saldo Kas</h5>
                    <h3>Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                </div>
                <i class="bi bi-cash-stack fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-info text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Pengurus</h5>
                    <h3><?= $jumlah_pengurus ?> Orang</h3>
                </div>
                <i class="bi bi-people-fill fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-success text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Inventaris</h5>
                    <h3><?= $total_inventaris ?> Unit</h3>
                </div>
                <i class="bi bi-box-seam fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
         <div class="card bg-warning text-dark">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Kurban</h5>
                    <h3><?= $conn->query("SELECT COUNT(*) FROM raka_kurban")->fetch_row()[0] ?> Pendaftar</h3>
                </div>
                <i class="bi bi-clipboard-heart fs-1"></i>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4>Selamat Datang di Sistem Informasi Masjid Jamiatul 'Ilmi</h4>
    </div>
    <div class="card-body">
        <p>Anda login sebagai <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong> dengan peran <strong><?= ucfirst($_SESSION['role']) ?></strong>.</p>
        <p>Gunakan menu navigasi di atas untuk mengelola data masjid.</p>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>