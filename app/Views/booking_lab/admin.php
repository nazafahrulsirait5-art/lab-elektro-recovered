<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0 text-dark" style="font-family: 'Open Sans', sans-serif;">Persetujuan Booking Lab</h3>
                <p class="text-muted small mb-0">Kelola pengajuan pemakaian ruangan lab dari mahasiswa.</p>
            </div>
        </div>
    </div>

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
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small" style="border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th class="ps-4 py-3" width="50">#</th>
                                    <th class="py-3">MAHASISWA</th>
                                    <th class="py-3">JADWAL</th>
                                    <th class="py-3">KEPERLUAN</th>
                                    <th class="py-3">STATUS</th>
                                    <th class="text-end pe-4 py-3" width="200">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($bookings)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">Belum ada pengajuan booking lab.</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php $no=1; foreach($bookings as $b): 
                                        $badgeClass = 'bg-secondary';
                                        if ($b['status'] == 'Disetujui') $badgeClass = 'bg-success';
                                        if ($b['status'] == 'Ditolak') $badgeClass = 'bg-danger';
                                        if ($b['status'] == 'Menunggu Persetujuan') $badgeClass = 'bg-warning text-dark';
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <span class="fw-bold text-dark d-block"><?= esc($b['nama_lengkap']) ?></span>
                                            <small class="text-muted"><?= esc($b['username']) ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><i class="far fa-calendar-alt text-primary me-1"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?></div>
                                            <small class="text-muted border rounded px-1 mt-1 d-inline-block"><i class="far fa-clock me-1"></i> <?= substr($b['jam_mulai'], 0, 5) ?> - <?= substr($b['jam_selesai'], 0, 5) ?> WIB</small>
                                        </td>
                                        <td>
                                            <p class="mb-0 small" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($b['keperluan']) ?>">
                                                <?= esc($b['keperluan']) ?>
                                            </p>
                                        </td>
                                        <td><span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2"><?= $b['status'] ?></span></td>
                                        <td class="text-end pe-4">
                                            <?php if($b['status'] == 'Menunggu Persetujuan'): ?>
                                                <a href="<?= base_url('booking/action/' . $b['id'] . '/Disetujui') ?>" class="btn btn-sm btn-success rounded-pill px-3 me-1" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="<?= base_url('booking/action/' . $b['id'] . '/Ditolak') ?>" class="btn btn-sm btn-danger rounded-pill px-3" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php elseif($b['status'] == 'Disetujui'): ?>
                                                <a href="<?= base_url('booking/action/' . $b['id'] . '/Selesai') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Tandai Selesai">
                                                    <i class="fas fa-flag-checkered me-1"></i> Selesai
                                                </a>
                                            <?php else: ?>
                                                -
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
        </div>
    </div>
</div>
<?= $this->endSection() ?>
