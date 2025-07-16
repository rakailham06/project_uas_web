<?php
session_start();

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_user() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'user']);
}

function redirect_if_not_logged_in() {
    // Sesuaikan path jika file berada di dalam folder admin
    $login_path = (basename(dirname($_SERVER['PHP_SELF'])) == 'admin') ? '../login.php' : 'login.php';
    if (!isset($_SESSION['id_user'])) {
        header("Location: $login_path");
        exit;
    }
}
?>