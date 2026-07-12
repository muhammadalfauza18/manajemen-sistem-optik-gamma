-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 12 Jul 2026 pada 12.07
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `optik_gamma`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keluhan` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `sph_od` decimal(4,2) DEFAULT NULL,
  `cyl_od` decimal(4,2) DEFAULT NULL,
  `axis_od` int(11) DEFAULT NULL,
  `add_od` decimal(4,2) DEFAULT NULL,
  `sph_os` decimal(4,2) DEFAULT NULL,
  `cyl_os` decimal(4,2) DEFAULT NULL,
  `axis_os` int(11) DEFAULT NULL,
  `add_os` decimal(4,2) DEFAULT NULL,
  `jenis_lensa` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `user_id`, `tanggal`, `keluhan`, `diagnosis`, `sph_od`, `cyl_od`, `axis_od`, `add_od`, `sph_os`, `cyl_os`, `axis_os`, `add_os`, `jenis_lensa`, `catatan`, `created_at`) VALUES
(2, 2, 6, '2026-07-08', 'ijn', 'jin', 1.00, 0.00, 90, 12.00, 0.00, 2.00, 145, 99.99, 'Photochromic', 'anjayg', '2026-07-08 18:03:54'),
(3, 4, 6, '2026-07-10', 'sakit', '', 2.00, 2.00, 22, 2.00, 2.00, 2.00, 2, 2.00, 'Bifocal', '', '2026-07-10 13:26:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `kode_pasien` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_visit` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `patients`
--

INSERT INTO `patients` (`id`, `kode_pasien`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `no_hp`, `email`, `alamat`, `created_at`, `last_visit`) VALUES
(2, 'PT-0001', 'Muhammad Alfauza99', 'Laki-laki', '2005-11-18', '087831146474', 'muhammadalfauza18@gmail.com', 'akmal', '2026-07-08 13:28:19', '2026-07-08 20:45:09'),
(3, 'PT-0003', 'teduh', 'Laki-laki', '2005-05-11', '081234567890', 'teduh@gmail.com', 'iaha', '2026-07-08 19:44:18', '2026-07-09 02:44:18'),
(4, 'PT-0004', 'july', 'Perempuan', '2006-02-23', '0872983132', 'mnmnmnm@gmail.com', 'rumah', '2026-07-10 13:25:03', '2026-07-10 20:25:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `kode_produk` varchar(20) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` enum('Frame','Lensa','Aksesoris') NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `harga_beli` decimal(12,2) DEFAULT 0.00,
  `harga_jual` decimal(12,2) DEFAULT 0.00,
  `stok` int(11) DEFAULT 0,
  `satuan` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `kode_produk`, `nama_produk`, `kategori`, `merk`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `deskripsi`, `created_at`) VALUES
(2, 'PRD002', 'lensa ini', 'Lensa', 'adam', 20000.00, 30000.00, 49, 'pcs', 'inilah', '2026-07-08 19:13:27'),
(3, 'PRD003', 'jj', 'Aksesoris', 'adam', 40000.00, 50000.00, 877, 'botol', 'ini', '2026-07-08 19:44:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `kode_transaksi` varchar(30) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medical_record_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `metode_pembayaran` enum('Cash','Transfer','QRIS') DEFAULT 'Cash',
  `total` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `kode_transaksi`, `patient_id`, `medical_record_id`, `user_id`, `tanggal`, `metode_pembayaran`, `total`, `created_at`) VALUES
(1, 'TRX0001', 2, NULL, 6, '2026-07-08', 'Cash', 130000.00, '2026-07-08 20:47:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `qty`, `harga`, `subtotal`) VALUES
(1, 1, 3, 2, 50000.00, 100000.00),
(2, 1, 2, 1, 30000.00, 30000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Karyawan') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `no_hp`, `email`, `alamat`, `foto`, `status`, `created_at`) VALUES
(5, 'fauza', 'kita', '$2y$10$suEZR/dgb1xsTfcyFErYEOyzp5k3WuMygGupoG0bxqVp1fiz5sAMW', 'Karyawan', '081234567890', 'muhammadalfauza18@gmail.com', 'iha', '1783531243_2120.jpg', 'Aktif', '2026-07-08 17:20:43'),
(6, 'teduh', 'teduh', '$2y$10$jE6Gn81m3/EHwr.rWuxM8ej4ZzQrRLnF00acEWC09fPeqTvICY/jO', 'Admin', '888888888888', 'muhammadalfauza18@gmail.com', '8', '1783533398_Logo-UMRI-yg-baru.jpg', 'Aktif', '2026-07-08 17:56:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_medical_patient` (`patient_id`),
  ADD KEY `fk_medical_user` (`user_id`);

--
-- Indeks untuk tabel `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pasien` (`kode_pasien`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_transaction_patient` (`patient_id`),
  ADD KEY `fk_transaction_user` (`user_id`),
  ADD KEY `fk_transaction_medical` (`medical_record_id`);

--
-- Indeks untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_transaction` (`transaction_id`),
  ADD KEY `fk_detail_product` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `fk_medical_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_medical_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transaction_medical` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `fk_detail_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
