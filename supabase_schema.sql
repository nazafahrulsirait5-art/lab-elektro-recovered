-- E-Lab Elektro Database Schema for Supabase (PostgreSQL)
-- Berdasarkan Revisi Bu Afnan: 3NF, Pemisahan Alat & Inventaris, Audit Logs terpisah

-- Table: users (PostgreSQL Compliant)
CREATE TABLE IF NOT EXISTS users (
  id_user SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  npm VARCHAR(20) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  foto_profil VARCHAR(255) DEFAULT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  role VARCHAR(50) NOT NULL CHECK (role IN ('admin', 'penjaga_lab', 'kaprodi', 'mahasiswa')),
  status_akun VARCHAR(20) DEFAULT 'aktif',
  email VARCHAR(100) DEFAULT NULL,
  reset_token VARCHAR(255) DEFAULT NULL,
  reset_expires TIMESTAMP DEFAULT NULL
);

-- Table: alat (Master Barang)
CREATE TABLE IF NOT EXISTS alat (
  id_alat SERIAL PRIMARY KEY,
  nama_alat VARCHAR(100) NOT NULL,
  merk VARCHAR(50) DEFAULT NULL
);

-- Table: inventaris (Fisik Barang)
CREATE TABLE IF NOT EXISTS inventaris (
  id_inventaris SERIAL PRIMARY KEY,
  id_alat INT NOT NULL REFERENCES alat(id_alat) ON DELETE CASCADE,
  kode_barcode VARCHAR(50) NOT NULL UNIQUE,
  kondisi_fisik VARCHAR(50) DEFAULT 'Bagus' CHECK (kondisi_fisik IN ('Bagus', 'Rusak', 'Maintenance')),
  status_ketersediaan VARCHAR(50) DEFAULT 'Tersedia' CHECK (status_ketersediaan IN ('Tersedia', 'Dipinjam'))
);

-- Table: transaksi (Peminjaman)
CREATE TABLE IF NOT EXISTS transaksi (
  id_transaksi SERIAL PRIMARY KEY,
  id_user INT NOT NULL REFERENCES users(id_user) ON DELETE CASCADE,
  id_inventaris INT NOT NULL REFERENCES inventaris(id_inventaris) ON DELETE CASCADE,
  tanggal_pinjam DATE NOT NULL,
  tanggal_jatuh_tempo DATE NOT NULL,
  tanggal_kembali DATE DEFAULT NULL,
  status_transaksi VARCHAR(50) NOT NULL DEFAULT 'Menunggu Persetujuan',
  status_alat VARCHAR(50) DEFAULT 'Bagus' CHECK (status_alat IN ('Bagus', 'Rusak', 'Hilang')),
  tipe_transaksi VARCHAR(50) DEFAULT 'Peminjaman Praktikum' CHECK (tipe_transaksi IN ('Peminjaman Praktikum', 'Peminjaman TA', 'Penelitian')),
  foto_pengembalian VARCHAR(255) DEFAULT NULL
);

-- Table: audit_logs
CREATE TABLE IF NOT EXISTS audit_logs (
  id_log SERIAL PRIMARY KEY,
  id_transaksi INT DEFAULT NULL REFERENCES transaksi(id_transaksi) ON DELETE SET NULL,
  id_inventaris INT DEFAULT NULL REFERENCES inventaris(id_inventaris) ON DELETE SET NULL,
  id_user INT NOT NULL REFERENCES users(id_user) ON DELETE CASCADE,
  action VARCHAR(100) NOT NULL,
  details TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Initial Database Seeding
INSERT INTO users (username, npm, password, nama_lengkap, role, status_akun) VALUES
('admin', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Utama', 'admin', 'aktif'),
('penjaga', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Penjaga Lab', 'penjaga_lab', 'aktif'),
('kaprodi', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kepala Program Studi', 'kaprodi', 'aktif'),
('250420501100004', '250420501100004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dwiky Ilham', 'mahasiswa', 'aktif'),
('2404205010006', '2404205010006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Misbah Anuari', 'mahasiswa', 'aktif'),
('250420501100002', '250420501100002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Naza Fahrul Sirait', 'mahasiswa', 'aktif'),
('250420501100007', '250420501100007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Mufadhdhal', 'mahasiswa', 'aktif'),
('250420501100003', '250420501100003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rana Sulthanah', 'mahasiswa', 'aktif')
ON CONFLICT (username) DO NOTHING;

INSERT INTO alat (id_alat, nama_alat, merk) VALUES
(1, 'Osiloskop Digital', 'Tektronix'),
(2, 'Multimeter Digital', 'Fluke'),
(3, 'Solder Station', 'Hakko')
ON CONFLICT (id_alat) DO NOTHING;

-- Reset sequence for alat because we inserted explicit IDs
SELECT setval(pg_get_serial_sequence('alat', 'id_alat'), COALESCE(MAX(id_alat), 1)) FROM alat;

INSERT INTO inventaris (id_inventaris, id_alat, kode_barcode, kondisi_fisik, status_ketersediaan) VALUES
(1, 1, 'OSC-001', 'Bagus', 'Tersedia'),
(2, 1, 'OSC-002', 'Bagus', 'Tersedia'),
(3, 2, 'MUL-001', 'Bagus', 'Tersedia'),
(4, 2, 'MUL-002', 'Rusak', 'Tersedia'),
(5, 3, 'SOL-001', 'Bagus', 'Dipinjam')
ON CONFLICT (id_inventaris) DO NOTHING;

-- Reset sequence for inventaris because we inserted explicit IDs
SELECT setval(pg_get_serial_sequence('inventaris', 'id_inventaris'), COALESCE(MAX(id_inventaris), 1)) FROM inventaris;
