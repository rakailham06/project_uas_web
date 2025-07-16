<?php
$page_title = "Data Pengurus";
include_once('../includes/header_user.php');
redirect_if_not_logged_in();

$pengurus = $conn->query("SELECT p.nama_lengkap, p.jabatan, p.status, u.role FROM raka_pengurus p JOIN raka_user u ON p.id_user = u.id_user ORDER BY u.role, p.nama_lengkap ASC");
?>
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-people-fill"></i> Daftar Pengurus dan User Terdaftar</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Jabatan</th>
                        <th>Status Akun</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $pengurus->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['jabatan']) ?></td>
                        <td>
                            <span class="badge bg-<?= $row['role']=='admin' ? 'danger' : 'primary' ?>">
                                <?= ucfirst($row['role']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info mt-3">
            Halaman ini menampilkan semua pengguna yang terdaftar di sistem, baik sebagai Admin maupun Pengurus/Jamaah.
        </div>
    </div>
</div>
<?php include_once('../includes/footer.php'); ?>