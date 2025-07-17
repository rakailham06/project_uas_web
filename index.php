<?php
$page_title = "Beranda";
include_once('includes/header_frontend.php');

$result_saldo = $conn->query("
    SELECT 
        COALESCE(SUM(CASE WHEN jenis_transaksi = 'Pemasukan' THEN jumlah ELSE 0 END), 0) - 
        COALESCE(SUM(CASE WHEN jenis_transaksi = 'Pengeluaran' THEN jumlah ELSE 0 END), 0) 
    AS saldo_akhir 
    FROM raka_keuangan
");

$saldo_akhir = 0; 
if ($result_saldo) {
    $saldo_akhir = $result_saldo->fetch_assoc()['saldo_akhir'] ?? 0;
}

$total_pengurus = $conn->query("SELECT COUNT(*) FROM raka_pengurus")->fetch_row()[0];
?>

<div class="container">
    <div class="p-5 mb-4 bg-light rounded-3 text-center">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Selamat Datang di Website Masjid jamiatul 'Ilmi</h1>
            <p class="fs-4">Pusat informasi kegiatan, keuangan, dan data jamaah masjid.</p>
        </div>
    </div>

    <div class="row text-center g-4 mb-5">
        <div class="col-md-6">
            <div class="card card-body shadow-sm">
                <i class="bi bi-cash-stack fs-1 text-success"></i>
                <h4 class="mt-2">Total Saldo Kas</h4>
                <p class="fs-3 fw-bold mb-0">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body shadow-sm">
                <i class="bi bi-people-fill fs-1 text-primary"></i>
                <h4 class="mt-2">Jumlah Pengurus</h4>
                <p class="fs-3 fw-bold mb-0"><?= $total_pengurus ?> Orang</p>
            </div>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-12">
            <div class="h-100 p-5 text-white bg-dark rounded-3">
                <h2>Visi & Misi Masjid</h2>
                <p>Menjadi pusat peribadatan dan peradaban Islam yang memakmurkan dan dimakmurkan oleh jamaah. Menyelenggarakan kegiatan dakwah, pendidikan, dan sosial yang bermanfaat bagi umat.</p>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>