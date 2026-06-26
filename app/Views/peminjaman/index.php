<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row g-4 mb-4">
    <?php if(in_array(session()->get('role'), ['admin', 'penjaga', 'penjaga_lab'])): ?>
    
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0" style="color: #f36c21;"><i class="fas fa-box-open me-2"></i> Meja Penjaga: Kelola Transaksi</h5>
            <div style="font-size: 0.8rem; color: #94a3b8;">Persetujuan, Pemantauan, dan Pengembalian Alat</div>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light rounded-pill btn-sm text-muted fw-bold border"><i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard</a>
        </div>

        <!-- 1. Menunggu Persetujuan Anda (Kuning) -->
        <div class="table-block-yellow shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-hourglass-half text-warning me-2"></i> Menunggu Persetujuan Anda</h6>
                <div class="badge bg-white text-dark rounded-pill shadow-sm" style="font-size: 0.7rem;">Prioritas 1</div>
            </div>
            
            <div class="bg-white rounded-4 overflow-hidden border">
                <table class="table table-borderless align-middle mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="border-bottom text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th class="ps-4 text-start">NIM / Username</th>
                            <th class="text-start">Nama Mahasiswa</th>
                            <th>Alat</th>
                            <th>JML</th>
                            <th>Batas Waktu</th>
                            <th class="pe-4 text-end">Aksi Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trans_menunggu)): ?>
                            <tr><td colspan="6" class="py-4 text-muted"><i class="fas fa-check-double fa-2x text-success mb-2 opacity-50 d-block"></i> Semua bersih! Tidak ada antrean pengajuan saat ini.</td></tr>
                        <?php else: ?>
                            <?php foreach($trans_menunggu as $t): ?>
                                <tr class="border-bottom">
                                    <td class="ps-4 text-start fw-bold text-muted"><?= $t['username'] ?></td>
                                    <td class="text-start fw-bold"><?= $t['nama_lengkap'] ?? $t['username'] ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $t['nama_alat'] ?></span></td>
                                    <td class="fw-bold"><?= $t['jumlah_pinjam'] ?></td>
                                    <td class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($t['batas_waktu'])) ?></td>
                                    <td class="pe-4 text-end">
                                        <a href="<?= base_url('peminjaman/setujui/' . $t['id']) ?>" class="btn btn-sm btn-success rounded-pill fw-bold" style="padding: 4px 16px;">Setujui</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Sedang Dipinjam & Pantauan Denda (Biru) -->
        <div class="table-block-blue shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark" style="color: #f36c21 !important;"><i class="fas fa-external-link-square-alt text-primary me-2"></i> Sedang Dipinjam & Pantauan Denda</h6>
                <div>
                    <button class="btn btn-primary rounded-pill btn-sm text-white fw-bold shadow-sm" style="background: #3b82f6; border: none; font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#scanQrModal"><i class="fas fa-qrcode me-1"></i> Scan QR Pengembalian</button>
                    <div class="badge bg-primary rounded-pill ms-2 shadow-sm" style="font-size: 0.7rem;">Aktif</div>
                </div>
            </div>

            <div class="bg-white rounded-4 overflow-hidden border">
                <table class="table table-borderless align-middle mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="border-bottom text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th class="ps-4 text-start">NIM / Username</th>
                            <th class="text-start">Nama Mahasiswa</th>
                            <th>Alat</th>
                            <th>Durasi Pinjam</th>
                            <th>Denda Aktif</th>
                            <th class="pe-4 text-end">Aksi Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trans_dipinjam)): ?>
                            <tr><td colspan="6" class="py-4 text-muted">Aman terkendali. Tidak ada yang sedang dipinjam.</td></tr>
                        <?php else: ?>
                            <?php foreach($trans_dipinjam as $t): ?>
                                <?php 
                                    $diff = strtotime($t['batas_waktu']) - time(); 
                                    $dendaAktif = ($diff < 0) ? floor(abs($diff) / 86400) * 5000 : 0;
                                ?>
                                <tr class="border-bottom row-dipinjam" data-id-alat="<?= $t['id_alat'] ?>" data-id-transaksi="<?= $t['id'] ?>" data-nama-mhs="<?= htmlspecialchars($t['nama_lengkap'] ?? $t['username']) ?>">
                                    <td class="ps-4 text-start fw-bold text-muted"><?= $t['username'] ?></td>
                                    <td class="text-start fw-bold"><?= $t['nama_lengkap'] ?? $t['username'] ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $t['nama_alat'] ?></span></td>
                                    <td class="fw-bold"><span class="<?= $diff < 0 ? 'text-danger' : 'text-warning' ?>"><?= floor(abs(strtotime($t['tanggal_pinjam']) - time())/86400) ?>/90 Hari</span></td>
                                    <td>
                                        <?php if($dendaAktif > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">Rp <?= number_format($dendaAktif, 0, ',', '.') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">Aman</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-sm btn-info text-white rounded-pill fw-bold" style="background: #0ea5e9; padding: 4px 16px; border: none;" data-bs-toggle="modal" data-bs-target="#modalKembali<?= $t['id'] ?>">
                                            <i class="fas fa-camera me-1"></i> Terima Pengembalian
                                        </button>
                                        
                                        <!-- Modal Pengembalian Parsial -->
                                        <div class="modal fade text-start" id="modalKembali<?= $t['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0" style="border-radius: 1.5rem;">
                                                    <div class="modal-header border-0 bg-light p-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="fas fa-undo-alt me-2" style="color: #0ea5e9;"></i> Verifikasi Kondisi Fisik</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="<?= base_url('peminjaman/kembali/' . $t['id']) ?>" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body p-4">
                                                            <p class="text-muted small mb-3">Mahasiswa <strong><?= $t['nama_lengkap'] ?? $t['username'] ?></strong> mengembalikan <span class="badge bg-light text-dark fw-bold border"><?= $t['jumlah_pinjam'] ?> unit</span> alat <strong><?= $t['nama_alat'] ?></strong>. Mohon periksa kondisi fisiknya.</p>
                                                            
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <label class="form-label text-success fw-bold" style="font-size: 0.8rem;">Kondisi Layak Pakai</label>
                                                                    <input type="number" name="qty_bagus" class="form-control fw-bold" value="<?= $t['jumlah_pinjam'] ?>" min="0" max="<?= $t['jumlah_pinjam'] ?>" required>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label text-danger fw-bold" style="font-size: 0.8rem;">Rusak / Hilang (Unit)</label>
                                                                    <input type="number" name="qty_rusak" class="form-control fw-bold" value="0" min="0" max="<?= $t['jumlah_pinjam'] ?>" required>
                                                                </div>
                                                                <div class="col-12 mt-3">
                                                                    <label class="form-label text-danger fw-bold" style="font-size: 0.8rem;">Nominal Tagihan Kerusakan/Kehilangan</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text bg-danger text-white fw-bold border-danger">Rp</span>
                                                                        <input type="number" name="denda_kerusakan" class="form-control fw-bold text-danger border-danger" value="0" min="0" placeholder="Contoh: 150000">
                                                                    </div>
                                                                    <div class="form-text" style="font-size: 0.70rem;">*Isi secara manual sesuai dengan harga estimasi servis atau harga perolehan alat. Kosongkan (0) jika alat aman.</div>
                                                                </div>
                                                                <div class="col-12 mt-3">
                                                                    <label class="form-label text-dark fw-bold" style="font-size: 0.8rem;"><i class="fas fa-camera me-1"></i> Foto Bukti Pengembalian</label>
                                                                    <input type="file" name="foto_pengembalian" class="form-control" accept="image/*" capture="environment">
                                                                    <div class="form-text" style="font-size: 0.70rem;">Bisa langsung memotret alat atau memilih foto dari galeri.</div>
                                                                </div>
                                                            </div>
                                                            <div class="alert mt-3 mb-0" style="font-size: 0.75rem; background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                                                                <i class="fas fa-info-circle me-1"></i> Denda kerusakan ini akan otomatis digabungkan dengan denda keterlambatan (jika mahasiswa telat mengembalikan).
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 p-4 pt-2">
                                                            <button type="submit" class="btn w-100 rounded-pill text-white fw-bold shadow-sm" style="background: #0ea5e9;">Selesaikan Pengembalian</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Riwayat Pengembalian Selesai (Hijau) -->
        <div class="table-block-green shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-check-circle text-success me-2"></i> Riwayat Pengembalian Selesai</h6>
                <div class="badge bg-success rounded-pill shadow-sm" style="font-size: 0.7rem;">Arsip</div>
            </div>

            <div class="bg-white rounded-4 overflow-hidden border opacity-75">
                <table class="table table-borderless align-middle mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="border-bottom text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th class="ps-4 text-start">NIM / Username</th>
                            <th class="text-start">Nama Mahasiswa</th>
                            <th>Alat</th>
                            <th>Tgl Dikembalikan</th>
                            <th>Denda Dibayar</th>
                            <th class="pe-4">Bukti Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trans_selesai)): ?>
                            <tr><td colspan="6" class="py-4 text-muted">Belum ada riwayat.</td></tr>
                        <?php else: ?>
                            <?php foreach($trans_selesai as $t): ?>
                                <tr class="border-bottom">
                                    <td class="ps-4 text-start fw-bold text-muted"><?= $t['username'] ?></td>
                                    <td class="text-start fw-bold"><?= $t['nama_lengkap'] ?? $t['username'] ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $t['nama_alat'] ?></span></td>
                                    <td class="text-muted"><i class="far fa-calendar-check me-1 text-success"></i> <?= date('d/m/Y H:i', strtotime($t['tanggal_kembali'])) ?></td>
                                    <td class="fw-bold"><?= ($t['denda'] > 0) ? 'Rp ' . number_format($t['denda'], 0, ',', '.') : 'Rp 0' ?></td>
                                    <td class="pe-4">
                                        <?php if(!empty($t['foto_pengembalian'])): ?>
                                            <a href="<?= base_url('uploads/pengembalian/' . $t['foto_pengembalian']) ?>" target="_blank">
                                                <i class="fas fa-image text-primary" style="font-size: 1.2rem; cursor: pointer;"></i>
                                            </a>
                                        <?php else: ?>
                                            <i class="fas fa-image text-muted opacity-25" style="font-size: 1.2rem;" title="Tidak ada foto"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-history text-primary me-2"></i> Log Peminjaman Alat (Mahasiswa)</h4>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm" style="background: #f36c21; border: none;">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted" style="font-size: 0.70rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th class="border-0 ps-4 py-3">ALAT & JUMLAH</th>
                                <th class="border-0">TGL PINJAM</th>
                                <th class="border-0">BATAS WAKTU</th>
                                <th class="border-0 text-center">STATUS</th>
                                <th class="border-0 text-center">DENDA</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.9rem;">
                            <?php if (empty($transaksi)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted"><i class="fas fa-box-open fa-2x mb-2 opacity-50"></i><br>Belum ada riwayat peminjaman.</div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($transaksi as $t): ?>
                                <?php 
                                    // Hitung denda aktif secara real-time jika status masih dipinjam
                                    $dendaTampil = $t['denda'];
                                    if ($t['status_pinjam'] == 'Dipinjam') {
                                        $diff = strtotime($t['batas_waktu']) - time();
                                        if ($diff < 0) {
                                            $dendaTampil = floor(abs($diff) / 86400) * 5000;
                                        }
                                    }
                                ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark"><?= $t['nama_alat'] ?></div>
                                        <span class="badge bg-light text-dark fw-bold border mt-1" style="font-size: 0.65rem;"><?= $t['jumlah_pinjam'] ?> UNIT</span>
                                    </td>
                                    <td class="text-muted"><i class="far fa-calendar-alt me-1 opacity-75"></i><?= date('d M Y', strtotime($t['tanggal_pinjam'])) ?></td>
                                    <td>
                                        <div class="fw-bold <?= (strtotime($t['batas_waktu']) < time() && $t['status_pinjam'] == 'Dipinjam') ? 'text-danger' : 'text-muted' ?>">
                                            <i class="far fa-clock me-1 opacity-75"></i><?= date('d M Y', strtotime($t['batas_waktu'])) ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if($t['status_pinjam'] == 'Menunggu Persetujuan' || $t['status_pinjam'] == 'Menunggu'): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning-subtle"><i class="fas fa-hourglass-half me-1"></i> Menunggu Persetujuan</span>
                                        <?php elseif($t['status_pinjam'] == 'Dipinjam'): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 border border-primary-subtle"><i class="fas fa-external-link-alt me-1"></i> Sedang Dipinjam</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success-subtle"><i class="fas fa-check-double me-1"></i> Dikembalikan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center fw-bold text-<?= ($dendaTampil > 0) ? 'danger' : 'muted' ?>">
                                        <?= ($dendaTampil > 0) ? 'Rp ' . number_format($dendaTampil, 0, ',', '.') : '-' ?>
                                        <?php if($t['status_pinjam'] == 'Dipinjam' && $dendaTampil > 0): ?>
                                            <div style="font-size: 0.65rem;" class="text-danger mt-1">Denda Aktif</div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if(in_array(session()->get('role'), ['admin', 'penjaga', 'penjaga_lab'])): ?>
    <!-- Modal Scan QR -->
    <div class="modal fade" id="scanQrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 1.5rem;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fas fa-qrcode me-2"></i> Scan QR Alat Laboratorium</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeScanModal"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <p class="text-muted small mb-3">Arahkan kamera ke QR Code yang tertempel pada fisik alat. Sistem akan otomatis memproses pengembalian.</p>
                    <div id="qr-reader" style="width: 100%; border-radius: 10px; overflow: hidden; border: 2px solid #e2e8f0;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required JS for QR Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var html5QrcodeScanner = null;
            var scanModal = document.getElementById('scanQrModal');
            
            if(scanModal) {
                scanModal.addEventListener('shown.bs.modal', function () {
                    // Initialize scanner
                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "qr-reader", 
                        { fps: 10, qrbox: {width: 250, height: 250} },
                        false
                    );
                    
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                });

                scanModal.addEventListener('hidden.bs.modal', function () {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.clear().catch(error => {
                            console.error("Failed to clear html5QrcodeScanner. ", error);
                        });
                    }
                });
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Hentikan scanner setelah berhasil
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear();
                }

                // Format yang diharapkan: ELAB-ALAT-123
                if(decodedText.startsWith("ELAB-ALAT-")) {
                    var idAlat = decodedText.replace("ELAB-ALAT-", "");
                    
                    // Cari baris transaksi di DOM
                    var rows = document.querySelectorAll('.row-dipinjam[data-id-alat="' + idAlat + '"]');
                    
                    if(rows.length === 0) {
                        alert("Alat ini tidak tercatat sedang dipinjam dalam sistem saat ini.");
                    } else if (rows.length === 1) {
                        var idTransaksi = rows[0].getAttribute('data-id-transaksi');
                        var namaMhs = rows[0].getAttribute('data-nama-mhs');
                        
                        if(confirm('Proses pengembalian alat dari mahasiswa: ' + namaMhs + '?')) {
                            // Tampilkan modal verifikasi kondisi fisik
                            var returnModal = new bootstrap.Modal(document.getElementById('modalKembali' + idTransaksi));
                            returnModal.show();
                        }
                    } else {
                        // Jika lebih dari 1 mahasiswa meminjam tipe alat yang sama
                        alert("Terdapat lebih dari 1 mahasiswa yang sedang meminjam alat/unit ini. Silakan klik tombol 'Terima & Foto' secara manual pada baris mahasiswa yang bersangkutan.");
                    }
                    
                    // Tutup modal
                    document.getElementById('closeScanModal').click();
                } else {
                    alert("QR Code tidak valid atau bukan milik E-Lab Elektro.");
                    document.getElementById('closeScanModal').click();
                }
            }

            function onScanFailure(error) {
                // Abaikan jika tidak mendeteksi
            }
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>
