<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Manajemen Inventaris Alat</h4>
        <div class="d-flex gap-2">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light rounded-pill px-4 text-muted border">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
            <?php if(in_array(session()->get('role'), ['admin', 'penjaga_lab', 'penjaga'])): ?>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Tambah Alat Baru
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Tools Table -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small" style="letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>NAMA ALAT</th>
                        <th>MERK</th>
                        <th class="text-center">TERSEDIA</th>
                        <th class="text-center">MAINTENANCE</th>
                        <th class="text-center">RUSAK</th>
                        <th class="text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($alat as $index => $a): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="ps-4 text-muted"><?= $index + 1 ?></td>
                        <td class="fw-bold text-dark"><?= $a['nama_alat'] ?></td>
                        <td class="text-muted small"><?= $a['merk'] ?></td>
                        <td class="text-center">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                <?= $a['jumlah_tersedia'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                <?= $a['jumlah_maintenance'] ?? 0 ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                <?= $a['jumlah_rusak'] ?? 0 ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <?php if(in_array(session()->get('role'), ['admin', 'penjaga_lab', 'penjaga', 'kaprodi'])): ?>
                                    <a href="<?= base_url('alat/qr/' . $a['id']) ?>" target="_blank" class="btn btn-sm btn-light text-primary" data-bs-toggle="tooltip" title="Cetak QR Code">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if(in_array(session()->get('role'), ['admin', 'penjaga_lab', 'penjaga'])): ?>
                                    <button class="btn btn-sm btn-light text-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $a['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= base_url('alat/delete/' . $a['id']) ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Hapus alat ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if(session()->get('role') == 'mahasiswa'): ?>
                                    <button class="btn btn-sm btn-primary ms-2 rounded-pill px-3 btn-add-to-cart" style="background-color: #f97316; border: none;" data-id="<?= $a['id'] ?>" data-nama="<?= $a['nama_alat'] ?>" data-stok="<?= $a['jumlah_tersedia'] ?>">
                                        <i class="fas fa-plus fa-sm"></i> Keranjang
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $a['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 card-glass">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Edit Data Alat</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= base_url('alat/update/' . $a['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Alat</label>
                                            <input type="text" name="nama_alat" class="form-control" value="<?= $a['nama_alat'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Merk</label>
                                            <input type="text" name="merk" class="form-control" value="<?= $a['merk'] ?>" required>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-primary small fw-bold">TOTAL</label>
                                                <input type="number" name="jumlah_total" class="form-control" value="<?= $a['jumlah_total'] ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-warning small fw-bold">MAINTENANCE</label>
                                                <input type="number" name="jumlah_maintenance" class="form-control" value="<?= $a['jumlah_maintenance'] ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-danger small fw-bold">RUSAK</label>
                                                <input type="number" name="jumlah_rusak" class="form-control" value="<?= $a['jumlah_rusak'] ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-primary rounded-pill w-100">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 card-glass">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Inventaris Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('alat/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Digital Oscilloscope" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merk</label>
                        <input type="text" name="merk" class="form-control" placeholder="Contoh: Tektronix" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Unit</label>
                        <input type="number" name="jumlah_total" class="form-control" value="1" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary rounded-pill w-100">Tambahkan ke Inventaris</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
