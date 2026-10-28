-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 04:18 PM
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
-- Database: `db_inventaris`
--
CREATE DATABASE IF NOT EXISTS `db_inventaris` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_inventaris`;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `kategori`, `jumlah`, `harga`, `created_at`) VALUES
(1, 'BRG01', 'LAPTOP ASUS VIVOBOOK 14', 'elektronik', 12, 8750000.00, '2026-10-27 13:59:38'),
(2, 'BRG02', 'PRINTER EPSON L3210', 'elektronik', 4, 2850000.00, '2026-10-27 13:59:38'),
(3, 'BRG03', 'MONITOR LG 24 INCH', 'elektronik', 15, 1650000.00, '2026-10-27 13:59:38'),
(4, 'BRG04', 'MOUSE WIRELESS LOGITECH', 'elektronik', 30, 185000.00, '2026-10-27 13:59:38'),
(5, 'BRG05', 'MEJA KERJA KANTOR', 'furniture', 20, 1250000.00, '2026-10-27 13:59:38'),
(6, 'BRG06', 'KURSI KERJA ERGONOMIS', 'furniture', 18, 975000.00, '2026-10-27 13:59:38'),
(7, 'BRG07', 'LEMARI ARSIP BESI 4 LACI', 'furniture', 6, 2100000.00, '2026-10-27 13:59:38'),
(8, 'BRG08', 'KERTAS HVS A4 70 GSM', 'atk', 50, 52000.00, '2026-10-27 13:59:38'),
(9, 'BRG09', 'PULPEN GEL HITAM (BOX)', 'atk', 25, 36000.00, '2026-10-27 13:59:38'),
(10, 'BRG10', 'SPIDOL WHITEBOARD (BOX)', 'atk', 10, 48000.00, '2026-10-27 13:59:38'),
(11, 'BRG11', 'ROUTER TP-LINK ARCHER C6', 'jaringan', 5, 549000.00, '2026-10-27 13:59:38'),
(12, 'BRG12', 'SWITCH 8 PORT GIGABIT', 'jaringan', 7, 325000.00, '2026-10-27 13:59:38'),
(13, 'BRG13', 'KABEL LAN CAT6 305 METER', 'jaringan', 2, 1450000.00, '2026-10-27 13:59:38'),
(14, 'BRG14', 'SAPU DAN PENGKI SET', 'kebersihan', 12, 45000.00, '2026-10-27 13:59:38'),
(15, 'BRG15', 'ALAT PEL LANTAI', 'kebersihan', 8, 85000.00, '2026-10-27 13:59:38'),
(16, 'BRG16', 'TEMPAT SAMPAH STAINLESS', 'kebersihan', 14, 120000.00, '2026-10-27 13:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$w8L7aK4ZR/R9UbrrflJKCuH/HmTaSHY33lCBfdWvci1iFlcn5xd0m');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
