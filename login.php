<?php
include 'includes/db.php';
session_start();

// Jika sudah login, redirect ke dashboard yang sesuai
if (isset($_SESSION['id_user'])) {
    $redirect_path = $_SESSION['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php';
    header("Location: $redirect_path");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $query = $conn->prepare("SELECT u.id_user, u.username, u.role, p.id_pengurus, p.nama_lengkap FROM raka_user u JOIN raka_pengurus p ON u.id_user = p.id_user WHERE u.username = ? AND u.password = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['id_pengurus'] = $row['id_pengurus'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
        $_SESSION['role'] = $row['role'];

        // Logika Redirect Baru
        if ($row['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - SIMAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
            background: linear-gradient(120deg, #2193b0 0%, #6dd5ed 100%);
            min-height: 100vh;
            background-image: url('bg1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Login SIMAS Masjid Jamiatul 'Ilmi</h3>
            <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
                <div class="alert alert-success">Registrasi berhasil! Silakan login.</div>
            <?php endif; ?>
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3"><input type="text" class="form-control" name="username" placeholder="Username" required></div>
                <div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3"><a href="register.php">Belum punya akun? Register</a></div>
        </div>
    </div>
</div>
</body>
</html>