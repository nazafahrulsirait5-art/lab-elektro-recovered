<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row g-4 mb-5">
    <?php if(session()->get('role') == 'admin'): ?>
        <!-- ===== ADMIN: Dashboard Overview ===== -->
        <div class="col-12 mb-3">
            <h5 class="fw-bold text-dark mb-3">Dashboard Overview</h5>
            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card-modern">
                        <div class="stat-title">
                            <div class="stat-icon-square icon-orange"><i class="fas fa-box"></i></div>
                            Total Inventaris
                        </div>
                        <div class="stat-value-big"><?= number_format($total_alat) ?></div>
                        <div class="stat-subtext">Total Alat: <?= $total_alat ?> | Tersedia: <?= $total_tersedia ?></div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-modern">
                        <div class="stat-title">
                            <div class="stat-icon-square icon-red"><i class="fas fa-exclamation-triangle"></i></div>
                            Menunggu Persetujuan
                        </div>
                        <div class="stat-value-big"><?= number_format($menunggu_persetujuan) ?></div>
                        <div class="stat-subtext text-danger">Transaksi Perlu Validasi</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-modern">
                        <div class="stat-title">
                            <div class="stat-icon-square icon-orange"><i class="fas fa-shopping-cart"></i></div>
                            Peminjaman Aktif
                        </div>
                        <div class="stat-value-big"><?= number_format($pinjam_aktif) ?></div>
                        <div class="stat-subtext">Alat Sedang Dipinjam</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-modern">
                        <div class="stat-title">
                            <div class="stat-icon-square icon-orange"><i class="fas fa-flask"></i></div>
                            Total Mahasiswa
                        </div>
                        <div class="stat-value-big"><?= number_format($total_mahasiswa) ?></div>
                        <div class="stat-subtext">Akun Pengguna Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

    <?php if(session()->get('role') == 'mahasiswa' && !empty($overdue_mahasiswa)): ?>
        <div class="col-12 mt-4">
            <div class="p-4 rounded-4 border-0 shadow-lg position-relative overflow-hidden" 
                 style="background: linear-gradient(135deg, #7f1d1d 0%, #b91c1c 100%); color: white; min-height: 90px; box-shadow: 0 10px 30px -10px rgba(185, 28, 28, 0.45) !important;">
                
                <!-- Background decorative glowing circles -->
                <div style="position: absolute; right: -30px; top: -30px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(239, 68, 68, 0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                <div style="position: absolute; left: -20px; bottom: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                <div class="d-flex align-items-center flex-column flex-md-row gap-3 position-relative" style="z-index: 1;">
                    <!-- Pulse Icon Wrapper -->
                    <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle" style="width: 54px; height: 54px; flex-shrink: 0;">
                        <i class="fas fa-exclamation-triangle fa-lg text-warning pulse-animation" style="color: #f59e0b !important;"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-grow-1 text-center text-md-start">
                        <h6 class="fw-bold mb-1" style="font-size: 1.05rem; letter-spacing: 0.5px; text-transform: uppercase; color: #fef08a;">
                            PERINGATAN: KETERLAMBATAN PENGEMBALIAN!
                        </h6>
                        <p class="mb-0 text-white-50" style="font-size: 0.88rem; line-height: 1.5;">
                            Anda memiliki <strong class="text-white"><?= count($overdue_mahasiswa) ?></strong> alat yang telah melewati batas waktu peminjaman. Sistem peminjaman baru Anda dibekukan sementara. Segera kembalikan alat ke laboratorium untuk menghindari denda.
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-2 mt-md-0">
                        <a href="<?= base_url('peminjaman/riwayat') ?>" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm border-0 transition-hover d-flex align-items-center" 
                           style="background: #f59e0b; color: #7f1d1d; font-size: 0.8rem; height: 38px;">
                            <i class="fas fa-receipt me-2"></i> Cek Riwayat & Denda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <style>
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.15); }
                100% { transform: scale(1); }
            }
            .pulse-animation {
                animation: pulse 1.8s infinite ease-in-out;
            }
            .transition-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .transition-hover:hover {
                transform: translateY(-2px);
                background: #fbbf24 !important;
                box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
                color: #7f1d1d !important;
            }
        </style>
    <?php endif; ?>


    <?php if(in_array(session()->get('role'), ['admin', 'kaprodi'])): ?>
        <!-- ===== ADMIN: Analytics & Overdue ===== -->
        <div class="row g-4 mb-4">
            <!-- Charts Section -->
            <div class="col-12 col-lg-8">
                <div class="stat-card-modern p-4 h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-chart-bar text-primary me-2"></i> Top 5 Alat Paling Sering Dipinjam</h6>
                    <div style="height: 300px;">
                        <canvas id="topToolsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="stat-card-modern p-4 h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-chart-pie text-success me-2"></i> Kesehatan Inventaris</h6>
                    <div style="height: 300px;">
                        <canvas id="inventoryHealthChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Overdue Table -->
            <?php if(!empty($all_overdue)): ?>
            <div class="col-12">
                <div class="stat-card-modern p-0 overflow-hidden" style="border: 1px solid #fca5a5;">
                    <div class="bg-danger text-white p-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Daftar Keterlambatan Pengembalian (Overdue)</h6>
                        <span class="badge bg-white text-danger rounded-pill"><?= count($all_overdue) ?> Kasus Aktif</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead style="background-color: #fef2f2; color: #991b1b; font-size: 0.8rem;">
                                <tr>
                                    <th class="ps-3 text-start">NAMA MAHASISWA</th>
                                    <th>ALAT DIPINJAM</th>
                                    <th>TGL KEMBALI SEHARUSNYA</th>
                                    <th>LAMA TERLAMBAT</th>
                                    <th class="pe-3">ESTIMASI DENDA</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.9rem;">
                                <?php foreach($all_overdue as $t): ?>
                                <?php 
                                    $diff = time() - strtotime($t['batas_waktu']);
                                    $hari_telat = floor($diff / 86400);
                                    $estimasi_denda = $hari_telat * 5000;
                                ?>
                                <tr style="background-color: #fffaf5;">
                                    <td class="ps-3 text-start fw-bold text-dark"><?= $t['nama_lengkap'] ?> <br><small class="text-muted fw-normal"><?= $t['username'] ?></small></td>
                                    <td><span class="badge bg-light text-dark border"><?= $t['nama_alat'] ?></span> (<?= $t['jumlah_pinjam'] ?> Unit)</td>
                                    <td class="text-danger fw-bold"><i class="far fa-calendar-times me-1"></i> <?= date('d M Y', strtotime($t['batas_waktu'])) ?></td>
                                    <td class="fw-bold text-danger"><?= $hari_telat ?> Hari</td>
                                    <td class="pe-3 fw-bold text-danger">Rp <?= number_format($estimasi_denda, 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(session()->get('role') == 'mahasiswa'): ?>
        <!-- ===== MAHASISWA: Modul Praktikum ===== -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="stat-card-modern p-4 border-0 shadow-sm" style="border-top: 4px solid #fca5a5 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-file-pdf text-danger me-2"></i> Modul Praktikum</h5>
                        <span class="badge bg-light text-muted border">Bahan Bacaan & Panduan</span>
                    </div>
                    <div class="row g-3">
                        <?php if(empty($modul_praktikum)): ?>
                            <div class="col-12">
                                <div class="text-center p-4 text-muted border rounded-3 bg-light">
                                    <i class="fas fa-folder-open fa-2x mb-2 text-secondary"></i>
                                    <p class="mb-0 small">Belum ada modul praktikum yang diupload oleh Penjaga Lab.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach($modul_praktikum as $m): 
                                $ext = strtolower(pathinfo($m['file_modul'], PATHINFO_EXTENSION));
                                $isPdf = ($ext === 'pdf');
                                $iconClass = $isPdf ? 'fa-file-pdf' : 'fa-file-excel';
                                $colorClass = $isPdf ? 'danger' : 'success';
                            ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 border shadow-sm rounded-3 hover-lift" style="background-color: #fff; transition: transform 0.2s;">
                                        <div class="card-body p-3 d-flex flex-column">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="bg-<?= $colorClass ?> bg-opacity-10 text-<?= $colorClass ?> rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                    <i class="fas <?= $iconClass ?> fa-lg"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem; line-height: 1.3;"><?= esc($m['judul']) ?></h6>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                        <i class="far fa-clock me-1"></i> <?= date('d M Y', strtotime($m['created_at'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="mt-auto pt-2 text-end border-top">
                                                <button type="button" class="btn btn-sm btn-outline-<?= $colorClass ?> rounded-pill px-3 mt-2 fw-bold" style="font-size: 0.8rem; width: 100%;" data-bs-toggle="modal" data-bs-target="#modulModal<?= $m['id'] ?>">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal for Modul <?= $m['id'] ?> -->
                                <div class="modal fade" id="modulModal<?= $m['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered <?= $isPdf ? 'modal-lg' : '' ?>">
                                        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem; overflow: hidden;">
                                            <div class="modal-header border-0 bg-light p-4">
                                                <h5 class="modal-title fw-bold text-dark"><i class="fas <?= $iconClass ?> text-<?= $colorClass ?> me-2"></i> Detail: <?= esc($m['judul']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            
                                            <?php if($isPdf): ?>
                                                <div class="modal-body p-0 text-center" style="background-color: #f8fafc; position: relative; height: 60vh;">
                                                    <iframe src="<?= base_url('uploads/modul/' . $m['file_modul']) ?>#toolbar=0" style="width: 100%; height: 100%; border: none;"></iframe>
                                                </div>
                                            <?php else: ?>
                                                <div class="modal-body p-5 text-center">
                                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px;">
                                                        <i class="fas fa-file-excel fa-4x"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-2"><?= esc($m['judul']) ?></h5>
                                                    <p class="text-muted mb-4">File ini adalah dokumen Microsoft Excel.</p>
                                                    <div class="alert alert-success border-0 bg-light text-start small mb-0 rounded-3">
                                                        <i class="fas fa-info-circle me-1"></i> File Excel tidak dapat di-preview di browser. Silakan klik tombol Download di bawah untuk membuka file ini di komputer Anda.
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="modal-footer border-0 p-4 bg-white d-flex justify-content-between align-items-center">
                                                <div class="text-muted small">
                                                    Diupload pada: <?= date('d M Y', strtotime($m['created_at'])) ?>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold me-2" data-bs-dismiss="modal">Tutup</button>
                                                    <a href="<?= base_url('uploads/modul/' . $m['file_modul']) ?>" download class="btn btn-<?= $colorClass ?> rounded-pill px-4 fw-bold shadow-sm">
                                                        <i class="fas fa-download me-2"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<div class="stat-card-modern p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-dark mb-0">Current Inventory List</h5>
        <div class="d-flex gap-2">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle border-0">
            <thead class="bg-light">
                <tr class="text-dark" style="font-size: 0.85rem; font-weight: 700;">
                    <th class="ps-3 border-0 rounded-start">Item Name</th>
                    <th class="border-0">Manufacturer</th>
                    <th class="border-0 text-center">Quantity</th>
                    <th class="border-0 text-center">Status</th>
                    <th class="border-0 text-end pe-3 rounded-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($alat as $item): ?>
                <?php 
                    $statusClass = 'bg-success text-white';
                    $statusIcon = 'fas fa-check-circle';
                    $statusText = 'Tersedia';
                    if($item['jumlah_tersedia'] == 0) {
                        $statusClass = 'bg-danger text-white';
                        $statusIcon = 'fas fa-times-circle';
                        $statusText = 'Habis';
                    } elseif ($item['jumlah_tersedia'] <= 2) {
                        $statusClass = 'bg-warning text-dark';
                        $statusIcon = 'fas fa-exclamation-triangle';
                        $statusText = 'Terbatas';
                    }
                ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="ps-3 py-3 fw-bold text-dark" style="font-size: 0.9rem;"><?= $item['nama_alat'] ?></td>
                    <td class="text-muted" style="font-size: 0.9rem;"><?= $item['merk'] ?></td>
                    <td class="text-center fw-bold" style="font-size: 0.9rem;"><?= $item['jumlah_tersedia'] ?> <span class="text-muted fw-normal">Unit</span></td>
                    <td class="text-center">
                        <span class="badge rounded-pill px-3 py-2 <?= $statusClass ?>" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="<?= $statusIcon ?> me-1"></i> <?= $statusText ?>
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <?php if (session()->get('role') == 'mahasiswa'): ?>
                            <?php if (!empty($overdue_mahasiswa)): ?>
                                <button class="btn btn-sm btn-secondary px-3" disabled title="Akses dibekukan: Ada alat yang terlambat dikembalikan">
                                    <i class="fas fa-ban me-1"></i> Dibekukan
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-orange-table btn-add-to-cart px-3" 
                                        data-id="<?= $item['id'] ?>" data-nama="<?= $item['nama_alat'] ?>" data-stok="<?= $item['jumlah_tersedia'] ?>">
                                    <i class="fas fa-plus me-1"></i> Pinjam
                                </button>
                            <?php endif; ?>
                        <?php elseif(session()->get('role') == 'kaprodi'): ?>
                            <a href="<?= base_url('alat') ?>" class="btn btn-sm btn-light text-primary border px-3">
                                <i class="fas fa-eye me-1"></i> Lihat Data
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('alat') ?>" class="btn btn-sm btn-light text-primary border px-3">
                                <i class="fas fa-cog me-1"></i> Kelola
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>





<!-- Add Chart.js to Layout or specifically here -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(in_array(session()->get('role'), ['admin', 'kaprodi'])): ?>
        // Top Tools Bar Chart
        var ctxBar = document.getElementById('topToolsChart').getContext('2d');
        var topToolsChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: [<?php foreach($top_tools as $tool) echo "'" . addslashes($tool['nama_alat']) . "', "; ?>],
                datasets: [{
                    label: 'Total Kali Dipinjam',
                    data: [<?php foreach($top_tools as $tool) echo $tool['total_pinjam'] . ", "; ?>],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Inventory Health Pie Chart
        var ctxPie = document.getElementById('inventoryHealthChart').getContext('2d');
        var healthChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Kondisi Bagus', 'Rusak / Maintenance'],
                datasets: [{
                    data: [<?= $total_stok_bagus ?>, <?= $total_stok_rusak ?>],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // Emerald green
                        'rgba(239, 68, 68, 0.8)'   // Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
