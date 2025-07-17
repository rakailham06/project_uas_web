<?php
$page_title = "Struktur Pengurus";
include_once('includes/header_frontend.php');
$pengurus = $conn->query("SELECT p.nama_lengkap, p.jabatan FROM raka_pengurus p WHERE p.status = 'Aktif' ORDER BY FIELD(p.jabatan, 'Ketua DKM', 'Sekretaris', 'Bendahara'), p.nama_lengkap ASC");
?>
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-center bg-white py-3">
            <h3>Struktur Kepengurusan Masjid</h3>
            <p class="mb-0">Periode Aktif Saat Ini</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light"><tr><th>No</th><th>Nama Lengkap</th><th>Jabatan</th></tr></thead>
                    <tbody>
                        <?php $no = 1; while ($row = $pengurus->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($row['jabatan']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>