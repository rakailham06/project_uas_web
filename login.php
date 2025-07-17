
<!DOCTYPE html>
<html>
<head>
    <title>Login - Masjid amiatul 'Ilmi</title>
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
            <h3 class="card-title text-center mb-4">Login Pengurus</h3>
            <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
                <div class="alert alert-success">Registrasi berhasil! Silakan login.</div>
            <?php endif; ?>
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3"><input type="text" class="form-control" name="username" placeholder="Username" required></div>
                <div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Kembali ke Beranda</a> </form>
            <div class="text-center mt-3"><a href="register.php">Belum punya akun? Register</a></div>
        </div>
    </div>
</div>
</body>
</html>