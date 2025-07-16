-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 11:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raka_masjid`
--

-- --------------------------------------------------------

--
-- Table structure for table `raka_inventaris`
--

CREATE TABLE `raka_inventaris` (
  `id_inventaris` int(11) NOT NULL,
  `id_pengurus_pencatat` int(11) DEFAULT NULL,
  `kode_barang` varchar(20) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `tanggal_perolehan` date DEFAULT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `lokasi_penyimpanan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raka_inventaris`
--

INSERT INTO `raka_inventaris` (`id_inventaris`, `id_pengurus_pencatat`, `kode_barang`, `nama_barang`, `jumlah`, `satuan`, `tanggal_perolehan`, `kondisi`, `lokasi_penyimpanan`) VALUES
(1, NULL, '101', 'Sapu', 1, '0', '2025-07-11', 'Baik', 'Gudang'),
(2, NULL, '102', 'Mikrofon', 4, '0', '2025-07-11', 'Baik', 'Gudang'),
(3, 2, '103', 'Karpet', 10, '0', '2025-07-11', 'Baik', 'Gudang');

-- --------------------------------------------------------

--
-- Table structure for table `raka_keuangan`
--

CREATE TABLE `raka_keuangan` (
  `id_transaksi` int(11) NOT NULL,
  `id_pengurus_pencatat` int(11) DEFAULT NULL,
  `jenis_transaksi` enum('Pemasukan','Pengeluaran') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal_transaksi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raka_keuangan`
--

INSERT INTO `raka_keuangan` (`id_transaksi`, `id_pengurus_pencatat`, `jenis_transaksi`, `kategori`, `keterangan`, `jumlah`, `tanggal_transaksi`) VALUES
(1, 2, 'Pemasukan', 'Infaq Jumat', '', 120000.00, '2025-07-11');

-- --------------------------------------------------------

--
-- Table structure for table `raka_kurban`
--

CREATE TABLE `raka_kurban` (
  `id_kurban` int(11) NOT NULL,
  `id_pengurus_pencatat` int(11) DEFAULT NULL,
  `nama_pekurban` varchar(100) NOT NULL,
  `jenis_hewan` enum('Sapi','Kambing') NOT NULL,
  `tahun_hijriah` int(4) NOT NULL,
  `status_pembayaran` enum('Lunas','Belum Lunas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raka_kurban`
--

INSERT INTO `raka_kurban` (`id_kurban`, `id_pengurus_pencatat`, `nama_pekurban`, `jenis_hewan`, `tahun_hijriah`, `status_pembayaran`) VALUES
(4, 2, 'Andyta Ilham Rakadiredja', 'Kambing', 1447, 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `raka_pengurus`
--

CREATE TABLE `raka_pengurus` (
  `id_pengurus` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raka_pengurus`
--

INSERT INTO `raka_pengurus` (`id_pengurus`, `id_user`, `nama_lengkap`, `jabatan`, `no_telepon`, `alamat`, `status`) VALUES
(2, 5, 'admin', 'Pengurus', NULL, NULL, 'Aktif'),
(3, 6, 'Rakadiredja', 'Jamaah', NULL, NULL, 'Aktif'),
(5, 8, 'test', 'Jamaah', NULL, NULL, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `raka_user`
--

CREATE TABLE `raka_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raka_user`
--

INSERT INTO `raka_user` (`id_user`, `username`, `password`, `role`, `created_at`) VALUES
(5, 'rakailham@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'admin', '2025-07-16 19:52:40'),
(6, 'raka@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'user', '2025-07-16 20:38:10'),
(8, 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', '2025-07-16 21:18:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `raka_inventaris`
--
ALTER TABLE `raka_inventaris`
  ADD PRIMARY KEY (`id_inventaris`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `fk_inventaris_pengurus` (`id_pengurus_pencatat`);

--
-- Indexes for table `raka_keuangan`
--
ALTER TABLE `raka_keuangan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_keuangan_pengurus` (`id_pengurus_pencatat`);

--
-- Indexes for table `raka_kurban`
--
ALTER TABLE `raka_kurban`
  ADD PRIMARY KEY (`id_kurban`),
  ADD KEY `fk_kurban_pengurus` (`id_pengurus_pencatat`);

--
-- Indexes for table `raka_pengurus`
--
ALTER TABLE `raka_pengurus`
  ADD PRIMARY KEY (`id_pengurus`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indexes for table `raka_user`
--
ALTER TABLE `raka_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `raka_inventaris`
--
ALTER TABLE `raka_inventaris`
  MODIFY `id_inventaris` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `raka_keuangan`
--
ALTER TABLE `raka_keuangan`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `raka_kurban`
--
ALTER TABLE `raka_kurban`
  MODIFY `id_kurban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `raka_pengurus`
--
ALTER TABLE `raka_pengurus`
  MODIFY `id_pengurus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `raka_user`
--
ALTER TABLE `raka_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `raka_inventaris`
--
ALTER TABLE `raka_inventaris`
  ADD CONSTRAINT `fk_inventaris_pengurus` FOREIGN KEY (`id_pengurus_pencatat`) REFERENCES `raka_pengurus` (`id_pengurus`) ON DELETE SET NULL;

--
-- Constraints for table `raka_keuangan`
--
ALTER TABLE `raka_keuangan`
  ADD CONSTRAINT `fk_keuangan_pengurus` FOREIGN KEY (`id_pengurus_pencatat`) REFERENCES `raka_pengurus` (`id_pengurus`) ON DELETE SET NULL;

--
-- Constraints for table `raka_kurban`
--
ALTER TABLE `raka_kurban`
  ADD CONSTRAINT `fk_kurban_pengurus` FOREIGN KEY (`id_pengurus_pencatat`) REFERENCES `raka_pengurus` (`id_pengurus`) ON DELETE SET NULL;

--
-- Constraints for table `raka_pengurus`
--
ALTER TABLE `raka_pengurus`
  ADD CONSTRAINT `raka_pengurus_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `raka_user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
