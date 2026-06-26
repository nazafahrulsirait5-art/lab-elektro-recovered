DROP DATABASE IF EXISTS lab_elektro;
CREATE DATABASE lab_elektro;
USE lab_elektro;

-- Table: users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `npm` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','penjaga_lab','kaprodi','mahasiswa') NOT NULL,
  `status_akun` varchar(20) DEFAULT 'aktif',
  `email` varchar(100) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: alat
CREATE TABLE IF NOT EXISTS `alat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_alat` varchar(100) NOT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `jumlah_total` int(11) NOT NULL DEFAULT 0,
  `jumlah_tersedia` int(11) NOT NULL DEFAULT 0,
  `jumlah_maintenance` int(11) NOT NULL DEFAULT 0,
  `jumlah_rusak` int(11) NOT NULL DEFAULT 0,
  `status` varchar(50) DEFAULT 'Tersedia',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `id_alat` int(11) NOT NULL,
  `jumlah_pinjam` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `batas_waktu` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status_pinjam` varchar(50) NOT NULL DEFAULT 'Menunggu Persetujuan',
  `denda` decimal(10,2) DEFAULT 0.00,
  `foto_pengembalian` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_alat` (`id_alat`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding users
INSERT INTO `users` (`username`, `npm`, `password`, `nama_lengkap`, `role`, `status_akun`) VALUES
('admin', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Utama', 'admin', 'aktif'),
('penjaga', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Penjaga Lab', 'penjaga_lab', 'aktif'),
('kaprodi', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kepala Program Studi', 'kaprodi', 'aktif'),
('250420501100004', '250420501100004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dwiky Ilham', 'mahasiswa', 'aktif'),
('2404205010006', '2404205010006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Misbah Anuari', 'mahasiswa', 'aktif'),
('250420501100002', '250420501100002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Naza Fahrul Sirait', 'mahasiswa', 'aktif'),
('250420501100007', '250420501100007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Mufadhdhal', 'mahasiswa', 'aktif'),
('250420501100003', '250420501100003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rana Sulthanah', 'mahasiswa', 'aktif');

INSERT INTO `alat` (`nama_alat`, `merk`, `jumlah_total`, `jumlah_tersedia`, `jumlah_maintenance`, `jumlah_rusak`, `status`) VALUES
('Signal Generator', 'Rigol DG1022Z', 6, 5, 1, 0, 'Tersedia'),
('Spectrum Analyzer', 'Keysight N9320B', 2, 2, 0, 0, 'Tersedia'),
('Oscilloscope Digital', 'Tektronix TBS1052B', 15, 12, 2, 1, 'Tersedia'),
('Mikroskop Elektron (SEM)', 'Hitachi TM4000', 1, 1, 0, 0, 'Tersedia'),
('Motor Stepper NEMA 17', 'Hanpose', 28, 25, 0, 3, 'Tersedia'),
('PLC Controller', 'Omron CP1E', 9, 8, 1, 0, 'Tersedia'),
('Multimeter Digital', 'Fluke 115', 35, 30, 0, 5, 'Tersedia'),
('Power Supply DC Adjustable', 'Korad KA3005D', 18, 15, 2, 1, 'Tersedia'),
('Function Generator', 'GW Instek AFG-21225', 11, 10, 1, 0, 'Tersedia'),
('Soldering Station', 'Hakko FX-888D', 25, 20, 3, 2, 'Tersedia'),
('LCR Meter', 'Keysight U1733C', 8, 7, 1, 0, 'Tersedia'),
('Logic Analyzer', 'Saleae Logic Pro 16', 5, 5, 0, 0, 'Tersedia'),
('Variable AC Power Supply', 'Tenma 72-1090', 4, 3, 1, 0, 'Tersedia'),
('RF Signal Generator', 'Rohde & Schwarz SMC100A', 3, 3, 0, 0, 'Tersedia'),
('Thermal Imaging Camera', 'FLIR E4', 2, 2, 0, 0, 'Tersedia'),
('Laser Cutter', 'Glowforge Plus', 1, 1, 0, 0, 'Tersedia'),
('3D Printer', 'Ultimaker S5', 3, 2, 1, 0, 'Tersedia'),
('CNC PCB Milling Machine', 'Bungard CCD', 1, 1, 0, 0, 'Tersedia'),
('Digital IC Tester', 'Minipro TL866II Plus', 10, 9, 0, 1, 'Tersedia'),
('Analog Training System', 'ETS-7000', 12, 10, 2, 0, 'Tersedia'),
('Digital Training System', 'ETS-8500', 12, 11, 1, 0, 'Tersedia'),
('Solder Fume Extractor', 'Weller WE1010', 15, 14, 0, 1, 'Tersedia'),
('Hot Air Rework Station', 'Quick 861DW', 6, 5, 1, 0, 'Tersedia'),
('Clamp Meter', 'Fluke 323', 20, 18, 0, 2, 'Tersedia'),
('Insulation Tester', 'Megger MIT415', 4, 4, 0, 0, 'Tersedia'),
('Frequency Counter', 'Keysight 53220A', 6, 6, 0, 0, 'Tersedia'),
('Power Quality Analyzer', 'Fluke 435-II', 2, 2, 0, 0, 'Tersedia'),
('Battery Tester', 'Hioki BT3554', 5, 4, 1, 0, 'Tersedia'),
('Lux Meter', 'Sanwa LX2', 10, 9, 0, 1, 'Tersedia'),
('Sound Level Meter', 'Extech 407730', 8, 8, 0, 0, 'Tersedia'),
('Tachometer Digital', 'Lutron DT-2236', 7, 7, 0, 0, 'Tersedia'),
('Gaussmeter', 'F.W. Bell 5180', 2, 2, 0, 0, 'Tersedia'),
('Decade Resistance Box', 'IET Labs RS-200', 14, 12, 1, 1, 'Tersedia'),
('Decade Capacitance Box', 'IET Labs CS-300', 14, 13, 0, 1, 'Tersedia'),
('Autotransformer', 'Staco Energy 1010V', 5, 5, 0, 0, 'Tersedia'),
('ESD Grounding Wrist Strap', '3M 2210', 50, 48, 0, 2, 'Tersedia'),
('Anti-Static Mat', 'Bertech ESD', 20, 20, 0, 0, 'Tersedia'),
('Temperature Calibration Bath', 'Fluke 6330', 2, 1, 1, 0, 'Tersedia'),
('Rheostat', 'Ohmite RDS', 12, 11, 0, 1, 'Tersedia'),
('High Voltage Probe', 'Tektronix P6015A', 4, 4, 0, 0, 'Tersedia');

-- Seeding transaksi (Peminjaman)
INSERT INTO `transaksi` (`username`, `id_alat`, `jumlah_pinjam`, `tanggal_pinjam`, `batas_waktu`, `tanggal_kembali`, `status_pinjam`, `denda`) VALUES
('250420501100004', 3, 2, '2026-05-20', '2026-05-27', NULL, 'Dipinjam', 0.00),
('2404205010006', 1, 1, '2026-05-18', '2026-05-25', NULL, 'Dipinjam', 10000.00),
('250420501100002', 7, 1, '2026-05-22', '2026-05-29', NULL, 'Dipinjam', 0.00),
('250420501100007', 5, 2, '2026-05-24', '2026-05-31', NULL, 'Dipinjam', 0.00),
('250420501100004', 1, 1, '2026-05-15', '2026-05-22', '2026-05-21', 'Kembali', 0.00),
('2404205010006', 3, 1, '2026-05-10', '2026-05-17', '2026-05-17', 'Kembali', 0.00),
('250420501100003', 10, 1, '2026-05-25', '2026-06-01', NULL, 'Menunggu Persetujuan', 0.00);
