<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $error = '';
    
    if (empty($nama_lengkap) || empty($username) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } else {
        $stmt_check = $conn->prepare("SELECT id_user FROM raka_user WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = 'Username sudah terdaftar, silakan gunakan yang lain.';
        } else {
            $password_hashed = md5($password);
            $role = 'user';
            $stmt_user = $conn->prepare("INSERT INTO raka_user (username, password, role) VALUES (?, ?, ?)");
            $stmt_user->bind_param("sss", $username, $password_hashed, $role);
            $stmt_user->execute();
            $id_user_baru = $stmt_user->insert_id;

            $jabatan_default = 'Jamaah';
            $stmt_pengurus = $conn->prepare("INSERT INTO raka_pengurus (id_user, nama_lengkap, jabatan) VALUES (?, ?, ?)");
            $stmt_pengurus->bind_param("iss", $id_user_baru, $nama_lengkap, $jabatan_default);
            if ($stmt_pengurus->execute()) {
                header("Location: login.php?register=success");
                exit;
            } else {
                $error = "Registrasi gagal, silakan coba lagi.";
                $conn->query("DELETE FROM raka_user WHERE id_user = $id_user_baru");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - SIMAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card p-4" style="width: 100%; max-width: 450px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Register Akun SIMAS</h3>
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                 <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>
            <div class="text-center mt-3"><a href="login.php">Sudah punya akun? Login</a></div>
        </div>
    </div>
</div>
</body>
</html>