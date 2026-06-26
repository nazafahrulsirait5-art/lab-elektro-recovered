<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0 text-dark" style="font-family: 'Open Sans', sans-serif;">Peminjaman Laboratorium</h3>
                <p class="text-muted small mb-0">Ajukan jadwal pemakaian ruangan lab agar tidak bentrok.</p>
            </div>
            <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#bookingModal">
                <i class="fas fa-calendar-plus me-2"></i> Buat Pengajuan
            </button>
        </div>
    </div>

    <!-- Menampilkan pesan error atau success -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Jadwal yang Terisi -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-list-alt text-primary me-2"></i> Jadwal Laboratorium Terkini (Hari Ini & Mendatang)</h6>
                    <p class="text-muted small mt-1">Gunakan tabel ini untuk melihat slot kosong sebelum mengajukan booking.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small" style="border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th class="ps-4 py-3" width="50">#</th>
                                    <th class="py-3">TANGGAL</th>
                                    <th class="py-3">JAM MULAI</th>
                                    <th class="py-3">JAM SELESAI</th>
                                    <th class="py-3">PEMINJAM (NIM/NAMA)</th>
                                    <th class="py-3">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($bookings)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-check fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">Belum ada jadwal terisi untuk hari ini dan seterusnya.</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php $no=1; foreach($bookings as $b): 
                                        $badgeClass = 'bg-secondary';
                                        if ($b['status'] == 'Disetujui') $badgeClass = 'bg-success';
                                        if ($b['status'] == 'Ditolak') $badgeClass = 'bg-danger';
                                        if ($b['status'] == 'Menunggu Persetujuan') $badgeClass = 'bg-warning text-dark';
                                    ?>
                                    <tr <?= ($b['username'] == session()->get('username')) ? 'style="background-color: #f0fdf4;"' : '' ?>>
                                        <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark"><i class="far fa-calendar-alt text-primary me-1"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?></td>
                                        <td><span class="badge bg-light text-dark border"><i class="far fa-clock me-1"></i> <?= substr($b['jam_mulai'], 0, 5) ?> WIB</span></td>
                                        <td><span class="badge bg-light text-dark border"><i class="far fa-clock me-1"></i> <?= substr($b['jam_selesai'], 0, 5) ?> WIB</span></td>
                                        <td>
                                            <?= ($b['username'] == session()->get('username')) ? '<span class="badge bg-primary rounded-pill me-1">Saya</span>' : '' ?>
                                            <?= esc($b['username']) ?>
                                        </td>
                                        <td><span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2"><?= $b['status'] ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Booking -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 bg-light p-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-calendar-plus me-2 text-primary"></i> Form Booking Laboratorium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('booking/store') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small">
                        <i class="fas fa-info-circle me-1"></i> Pastikan jam yang Anda pilih tidak bentrok dengan jadwal yang sudah ada (disetujui/menunggu).
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Tanggal Peminjaman</label>
                        <input type="date" name="tanggal" class="form-control form-control-lg rounded-3" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold text-muted small">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control form-control-lg rounded-3" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold text-muted small">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control form-control-lg rounded-3" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold text-muted small">Keperluan / Tujuan</label>
                        <textarea name="keperluan" class="form-control form-control-lg rounded-3" rows="3" placeholder="Contoh: Mengerjakan Tugas Akhir Pengukuran Komponen" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-light" style="border-radius: 0 0 1.5rem 1.5rem;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> Ajukan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
