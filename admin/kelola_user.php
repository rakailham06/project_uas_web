<?php
$page_title = "Kelola User";
include_once('../includes/header_admin.php');
redirect_if_not_logged_in();
if (!is_admin()) { exit("Akses Ditolak"); }

if (isset($_GET['delete'])) {
    $id_user = intval($_GET['delete']);
    if ($id_user > 1) {
        $conn->query("DELETE FROM raka_user WHERE id_user = $id_user");
    }
    header("Location: kelola_user.php");
    exit;
}
echo '<a href="dashboard.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>';
$users = $conn->query("SELECT u.id_user, u.username, u.role, p.nama_lengkap, p.jabatan FROM raka_user u LEFT JOIN raka_pengurus p ON u.id_user = p.id_user ORDER BY u.role, p.nama_lengkap");
?>
<div class="card">
    <div class="card-header"><h4>Manajemen Akun Pengguna</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead><tr><th>Nama Lengkap</th><th>Username</th><th>Jabatan</th><th>Role</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['jabatan']) ?></td>
                        <td><span class="badge bg-<?= $row['role'] == 'admin' ? 'danger' : 'secondary' ?>"><?= $row['role'] ?></span></td>
                        <td>
                            <?php if ($row['id_user'] > 1 && $_SESSION['id_user'] != $row['id_user']): // Cegah admin hapus dirinya sendiri atau admin utama ?>
                            <a href="?delete=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')"><i class="bi bi-trash"></i> Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once('../includes/footer.php'); ?>