-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jun 2025 pada 13.51
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
-- Database: `sahabat_karier`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `lamaran`
--

CREATE TABLE `lamaran` (
  `id_lamaran` int(11) NOT NULL,
  `id_pekerjaan` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `cv_path` varchar(255) NOT NULL,
  `portofolio_path` varchar(255) NOT NULL,
  `lamaran_path` varchar(255) NOT NULL,
  `tanggal_lamaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pekerjaan`
--

CREATE TABLE `pekerjaan` (
  `id_pekerjaan` int(11) NOT NULL,
  `id_perusahaan` int(11) NOT NULL,
  `judul_pekerjaan` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `gaji_minimum` int(11) NOT NULL,
  `gaji_maksimum` int(11) NOT NULL,
  `jenis_pekerjaan` enum('Penuh Waktu','Paruh Waktu','Freelance','Remote') NOT NULL,
  `deskripsi` text NOT NULL,
  `syarat_kualifikasi` text NOT NULL,
  `tanggal_uploud` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pekerjaan`
--

INSERT INTO `pekerjaan` (`id_pekerjaan`, `id_perusahaan`, `judul_pekerjaan`, `kategori`, `gaji_minimum`, `gaji_maksimum`, `jenis_pekerjaan`, `deskripsi`, `syarat_kualifikasi`, `tanggal_uploud`, `tanggal_deadline`) VALUES
(6, 1, 'Network Security Engineer', 'Teknologi Informasi', 6000000, 7500000, 'Penuh Waktu', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 07:10:51', '2025-06-09'),
(7, 1, 'Nyapu Halaman Rumah', 'Assistance', 15000000, 20000000, 'Freelance', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 07:16:58', '2025-06-11'),
(9, 2, 'Cyber Security Consultant', 'Teknologi Informasi', 15000000, 20000000, 'Penuh Waktu', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 08:27:51', '2025-06-20'),
(10, 3, 'Sales', 'Pemasaran', 4000000, 5000000, 'Penuh Waktu', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 08:36:05', '2025-06-21'),
(11, 4, 'Dosen Perguruan Tinggi', 'Pendidikan', 5000000, 8000000, 'Penuh Waktu', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 08:41:35', '2025-06-20'),
(12, 5, 'Pengajar Informatika', 'Pendidikan', 1000000, 2000000, 'Paruh Waktu', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 08:46:05', '2025-06-23'),
(13, 6, 'Admin Accounting', 'Keuangan', 3500000, 5000000, 'Remote', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 09:05:02', '2025-06-15'),
(14, 7, 'Host Live', 'Pemasaran', 3000000, 6000000, 'Freelance', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 09:06:00', '2025-06-19'),
(15, 8, 'Guru English', 'Pendidikan', 1500000, 3000000, 'Penuh Waktu', 'Monitor customer network infrastructure. Identify issues and errors before or as they occur. Ensure normal day-to-day operations. Report customer infrastructure conditions to Managers and customers. Collaborate with multi-functional teams to implement security controls and improve security posture. Collect and analyze feedback from customer SOPs for periodic improvement. Monitor and manage various firewalls. Monitor firewall logs and respond to security incidents. Remediate firewall vulnerabilities. Implement firewall security policies and rules. Provide support and guidance for secure endpoint use. Collaborate with department heads and subject matter experts to gather information and insights for SOP development. Responsible for SLAs provided by customers and other related tasks.', 'Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls. Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management. Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus. Excellent communication skills and ability to work well in a team. Strong analytical and problem-solving skills with attention to detail. Willing to be placed at the client\'s location in the Jakarta area with a shifting schedule. Willing to be contracted for the duration of the project. Experience: minimum 2 (two) years of experience as a Network and Security Engineer. Bachelor\'s or Master\'s degree in Computer Science, Information Technology, or related field.', '2025-06-10 09:06:58', '2025-06-30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `nama_perusahaan` varchar(100) NOT NULL,
  `logo_path` varchar(255) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `id_users`, `nama_perusahaan`, `logo_path`, `alamat`) VALUES
(1, 1, 'PT Esha Parama Technology', 'image/PTEsha.png', 'Jakarta Selatan, DKI Jakarta'),
(2, 3, 'PT TechTiera Services Indonesia', 'image/PTTechTiera.webp', 'Jakarta Selatan, DKI Jakarta'),
(3, 1, 'PT Karya Delitama', 'image/PTKaryaDelitama.webp', 'Tangerang, Banten'),
(4, 3, 'Yayasan Enam Peduli Pendidikan', 'image/YayasanEnamPeduliPendidikan.webp', 'Cibitung, Jawa Barat'),
(5, 3, 'Ganesha Operation Abianbase', 'image/GaneshaOperationAbianbase.webp', 'Mengwi, Bali'),
(6, 1, 'Alt Perfumery', 'image/AltPerfumery.webp', 'Jakarta Barat, DKI Jakarta'),
(7, 1, 'Fundo Indonesia', 'image/FundoIndonesia.webp', 'Jakarta Selatan, DKI Jakarta'),
(8, 3, 'Yayasan Pendidikan Smart Indonesia', 'image/YayasanPendidikanSmartIndonesia.webp', 'Bekasi, Jawa Barat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('jobseeker','company') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_users`, `email`, `password`, `role`) VALUES
(1, 'kotak@example.com', 'kotak', 'company'),
(2, 'lingkaran@example.com', 'lingkaran', 'jobseeker'),
(3, 'segitiga@example.com', 'segitiga', 'company');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `lamaran`
--
ALTER TABLE `lamaran`
  ADD PRIMARY KEY (`id_lamaran`),
  ADD KEY `fk_lamaran_pekerjaan` (`id_pekerjaan`),
  ADD KEY `fk_lamaran_users` (`id_users`);

--
-- Indeks untuk tabel `pekerjaan`
--
ALTER TABLE `pekerjaan`
  ADD PRIMARY KEY (`id_pekerjaan`),
  ADD KEY `fk_pekerjaan_perusahaan` (`id_perusahaan`);

--
-- Indeks untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`),
  ADD KEY `fk_perusahaan_user` (`id_users`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `lamaran`
--
ALTER TABLE `lamaran`
  MODIFY `id_lamaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pekerjaan`
--
ALTER TABLE `pekerjaan`
  MODIFY `id_pekerjaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id_perusahaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `lamaran`
--
ALTER TABLE `lamaran`
  ADD CONSTRAINT `fk_lamaran_pekerjaan` FOREIGN KEY (`id_pekerjaan`) REFERENCES `pekerjaan` (`id_pekerjaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lamaran_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pekerjaan`
--
ALTER TABLE `pekerjaan`
  ADD CONSTRAINT `fk_pekerjaan_perusahaan` FOREIGN KEY (`id_perusahaan`) REFERENCES `perusahaan` (`id_perusahaan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD CONSTRAINT `fk_perusahaan_user` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
