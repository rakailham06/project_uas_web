<?php
include_once(__DIR__ . '/db.php');
include_once(__DIR__ . '/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Sistem Informasi Masjid' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
        <i class="bi bi-moon-stars-fill"></i> Masjid Jamiatul 'Ilmi
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="frontend_keuangan.php">Info Keuangan</a></li>
        <li class="nav-item"><a class="nav-link" href="frontend_pengurus.php">Struktur Pengurus</a></li>
        <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
        <li class="nav-item ms-lg-3">
            <a href="login.php" class="btn btn-success"><i class="bi bi-box-arrow-in-right"></i> Login Pengurus</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<main class="py-4">