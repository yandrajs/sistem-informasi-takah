-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 05, 2025 at 12:05 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indexing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `denah`
--

CREATE TABLE `denah` (
  `denah_id` int NOT NULL,
  `nama` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(128) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denah`
--

INSERT INTO `denah` (`denah_id`, `nama`, `gambar`) VALUES
(34, 'Ruang Takah 1', '68e25d8c023ff.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `instansi`
--

CREATE TABLE `instansi` (
  `instansi_id` int NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_tabel` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instansi`
--

INSERT INTO `instansi` (`instansi_id`, `instansi`, `nama_tabel`) VALUES
(16, 'Kota Bandung', 'kota_bandung');

-- --------------------------------------------------------

--
-- Table structure for table `kota_bandung`
--

CREATE TABLE `kota_bandung` (
  `takah_id` int UNSIGNED NOT NULL,
  `ruangan_id` int NOT NULL,
  `no_rollopack` int NOT NULL,
  `no_lemari` int NOT NULL,
  `no_rak` int NOT NULL,
  `NIP` varchar(255) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `Instansi` varchar(255) NOT NULL,
  `D2NIP` tinyint(1) NOT NULL,
  `Ijazah` tinyint(1) NOT NULL,
  `DRH` tinyint(1) NOT NULL,
  `SKCPNS` tinyint(1) NOT NULL,
  `SKPNS` tinyint(1) NOT NULL,
  `SK_Perubahan_Data` tinyint(1) NOT NULL,
  `SK_Jabatan` tinyint(1) NOT NULL,
  `SK_Pemberhentian` tinyint(1) NOT NULL,
  `SK_Pensiun` tinyint(1) NOT NULL,
  `No_Panggil` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kota_bandung`
--

INSERT INTO `kota_bandung` (`takah_id`, `ruangan_id`, `no_rollopack`, `no_lemari`, `no_rak`, `NIP`, `Nama`, `Instansi`, `D2NIP`, `Ijazah`, `DRH`, `SKCPNS`, `SKPNS`, `SK_Perubahan_Data`, `SK_Jabatan`, `SK_Pemberhentian`, `SK_Pensiun`, `No_Panggil`) VALUES
(11, 16, 1, 1, 1, '197711212008014444', 'yandra', 'Kota Bandung', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(37, 16, 1, 1, 1, '197711212008011002', 'Renni Akbar, SH', 'Kota Bandung', 0, 0, 0, 0, 0, 0, 0, 0, 0, 2),
(38, 16, 1, 1, 1, '197711212008011003', 'Mujahidin Asa Putra, SE. AK', 'Kota Bandung', 0, 0, 0, 0, 0, 0, 0, 0, 0, 3),
(39, 16, 1, 1, 1, '197711212008011004', 'Gatot Wijanarko, S.Sos', 'Kota Bandung', 0, 0, 0, 0, 0, 0, 0, 0, 0, 4),
(40, 16, 1, 1, 1, '197711212008011005', 'Lia Susanti, SE. AK', 'Kota Bandung', 0, 0, 0, 0, 0, 0, 0, 0, 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `rollopack`
--

CREATE TABLE `rollopack` (
  `rollopack_id` int NOT NULL,
  `ruangan_id` int DEFAULT NULL,
  `nomor_rollopack` int DEFAULT NULL,
  `jumlah_lemari_per_rollopack` int DEFAULT NULL,
  `jumlah_rak_per_lemari` int NOT NULL,
  `kapasitas_per_rak` int NOT NULL,
  `instansi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_panggil_awal` int DEFAULT NULL,
  `no_panggil_akhir` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rollopack`
--

INSERT INTO `rollopack` (`rollopack_id`, `ruangan_id`, `nomor_rollopack`, `jumlah_lemari_per_rollopack`, `jumlah_rak_per_lemari`, `kapasitas_per_rak`, `instansi`, `no_panggil_awal`, `no_panggil_akhir`) VALUES
(70, 16, 1, 5, 6, 90, 'Kota Bandung', 1, 2700);

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `ruangan_id` int NOT NULL,
  `nama_ruangan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`ruangan_id`, `nama_ruangan`) VALUES
(16, 'Ruang Takah 1');

-- --------------------------------------------------------

--
-- Table structure for table `takah`
--

CREATE TABLE `takah` (
  `takah_id` int NOT NULL,
  `ruangan_id` int NOT NULL,
  `no_rollopack` int NOT NULL,
  `no_lemari` int NOT NULL,
  `no_rak` int NOT NULL,
  `NIP` char(18) COLLATE utf8mb4_general_ci NOT NULL,
  `Nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Kode_instansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Status` tinyint NOT NULL,
  `D2NIP` tinyint(1) NOT NULL,
  `Ijazah` tinyint(1) NOT NULL,
  `DRH` tinyint(1) NOT NULL,
  `SKCPNS` tinyint(1) NOT NULL,
  `SKPNS` tinyint(1) NOT NULL,
  `SK_Perubahan_Data` tinyint(1) NOT NULL,
  `SK_Jabatan` tinyint(1) NOT NULL,
  `SK_Pemberhentian` tinyint(1) NOT NULL,
  `SK_Pensiun` tinyint(1) NOT NULL,
  `No_Panggil` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `takah_bup`
--

CREATE TABLE `takah_bup` (
  `takah_bup_id` int NOT NULL,
  `ruangan_id` int NOT NULL,
  `no_rollopack` int NOT NULL,
  `no_lemari` int NOT NULL,
  `no_rak` int NOT NULL,
  `NIP` char(18) COLLATE utf8mb4_general_ci NOT NULL,
  `Nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Kode_instansi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `D2NIP` tinyint(1) NOT NULL,
  `Ijazah` tinyint(1) NOT NULL,
  `DRH` tinyint(1) NOT NULL,
  `SKCPNS` tinyint(1) NOT NULL,
  `SKPNS` tinyint(1) NOT NULL,
  `SK_Perubahan_Data` tinyint(1) NOT NULL,
  `SK_Jabatan` tinyint(1) NOT NULL,
  `SK_Pemberhentian` tinyint(1) NOT NULL,
  `SK_Pensiun` tinyint(1) NOT NULL,
  `No_Panggil` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `nama`, `username`, `password`, `role_id`) VALUES
(55, 'Admin', 'admin', '$2y$10$hFp47WVNtOESIpqkN2SxCuVUvescDJZbc9BfC8SkyWacL5ig3KqkK', 1),
(56, 'User', 'user', '$2y$10$VQWkrR6iYPTSwLBKdzZaf.2C5V7Bv6xO84v0167XlUcNw3RGbEDgu', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `access_menu_id` int NOT NULL,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`access_menu_id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(51, 2, 2),
(54, 1, 2),
(55, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `menu_id` int NOT NULL,
  `menu` varchar(128) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`menu_id`, `menu`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Menu');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `role_id` int NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`role_id`, `role`) VALUES
(1, 'Admin'),
(2, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `sub_menu_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `title` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`sub_menu_id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 1, 'Dashboard', 'admin', 'fas fa-fw fa-tachometer-alt', 1),
(2, 2, 'Cari Tata Letak', 'user', 'fas fa-fw fa-search', 1),
(3, 2, 'Denah', 'user/showdenah', 'fas fa-fw  fa-map', 1),
(4, 3, 'Pengaturan Menu', 'menu', 'fas fa-fw fa-solid fa-folder', 1),
(5, 3, 'Pengaturan Sub Menu', 'menu/submenu/0', 'fas fa-fw fa-folder-open', 1),
(6, 1, 'Kelola Denah', 'admin/managedenah', 'fas fa-fw fa-pen', 1),
(7, 1, 'Role', 'admin/role', 'fas fa-fw fa-user-tag', 1),
(8, 1, 'Buat Akun', 'admin/createaccount', 'fas fa-solid  fa-fw fa-user-plus', 0),
(9, 2, 'Input Data Pegawai', 'user/adddatapegawai', 'fas fa-fw fa-id-card', 1),
(14, 1, 'Kelola Akun', 'admin/manageaccount', 'fas fa-fw fa-user-cog', 1),
(21, 2, 'Import Excel', 'user/import', 'fas fa-fw fa-file-import', 1),
(27, 1, 'Kelola Usia BUP', 'admin/manage_bup', 'fas fa-fw fa-calendar-alt', 1),
(28, 1, 'Kelola Ruangan', 'admin/ruangan', 'fas fa-fw fa-door-open', 1),
(31, 1, 'Kelola Rollopack', 'admin/rollopack', 'fas fa-fw fa-archive', 1),
(32, 2, 'Katalog', 'user/katalog', 'fas fa-fw fa-th-list', 1),
(42, 2, 'Katalog BUP', 'user/katalogBUP', 'fas fa-fw fa-th-list', 1),
(43, 2, 'Ruangan/Rollopack', 'user/ruanganRollopack', 'fas fa-fw fa-door-open', 1),
(44, 1, 'Kelola Instansi', 'admin/manageinstansi', 'fas fa-fw  fa-map', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usia_bup`
--

CREATE TABLE `usia_bup` (
  `usia_bup_id` int NOT NULL,
  `usia_bup` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usia_bup`
--

INSERT INTO `usia_bup` (`usia_bup_id`, `usia_bup`) VALUES
(20, 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `denah`
--
ALTER TABLE `denah`
  ADD PRIMARY KEY (`denah_id`);

--
-- Indexes for table `instansi`
--
ALTER TABLE `instansi`
  ADD PRIMARY KEY (`instansi_id`);

--
-- Indexes for table `kota_bandung`
--
ALTER TABLE `kota_bandung`
  ADD PRIMARY KEY (`takah_id`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `rollopack`
--
ALTER TABLE `rollopack`
  ADD PRIMARY KEY (`rollopack_id`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`ruangan_id`);

--
-- Indexes for table `takah`
--
ALTER TABLE `takah`
  ADD PRIMARY KEY (`takah_id`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `takah_bup`
--
ALTER TABLE `takah_bup`
  ADD PRIMARY KEY (`takah_bup_id`),
  ADD KEY `takah_bup_ibfk_1` (`ruangan_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`access_menu_id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`sub_menu_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `usia_bup`
--
ALTER TABLE `usia_bup`
  ADD PRIMARY KEY (`usia_bup_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `denah`
--
ALTER TABLE `denah`
  MODIFY `denah_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `instansi`
--
ALTER TABLE `instansi`
  MODIFY `instansi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kota_bandung`
--
ALTER TABLE `kota_bandung`
  MODIFY `takah_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `rollopack`
--
ALTER TABLE `rollopack`
  MODIFY `rollopack_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `ruangan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `takah`
--
ALTER TABLE `takah`
  MODIFY `takah_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12110;

--
-- AUTO_INCREMENT for table `takah_bup`
--
ALTER TABLE `takah_bup`
  MODIFY `takah_bup_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `access_menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `sub_menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `usia_bup`
--
ALTER TABLE `usia_bup`
  MODIFY `usia_bup_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rollopack`
--
ALTER TABLE `rollopack`
  ADD CONSTRAINT `rollopack_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`ruangan_id`);

--
-- Constraints for table `takah`
--
ALTER TABLE `takah`
  ADD CONSTRAINT `takah_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`ruangan_id`);

--
-- Constraints for table `takah_bup`
--
ALTER TABLE `takah_bup`
  ADD CONSTRAINT `takah_bup_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`ruangan_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`);

--
-- Constraints for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD CONSTRAINT `user_access_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`menu_id`),
  ADD CONSTRAINT `user_access_menu_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`);

--
-- Constraints for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD CONSTRAINT `user_sub_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`menu_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
