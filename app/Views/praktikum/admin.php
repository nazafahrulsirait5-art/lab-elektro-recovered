<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0 text-dark" style="font-family: 'Open Sans', sans-serif;">Modul Praktikum</h3>
                <p class="text-muted small mb-0">Manajemen file PDF modul untuk mahasiswa</p>
            </div>
            <button class="btn btn-primary rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-2"></i> Upload Modul
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small" style="border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th class="ps-4 py-3" width="50">#</th>
                                    <th class="py-3">JUDUL MODUL</th>
                                    <th class="py-3">FILE</th>
                                    <th class="py-3">TANGGAL UPLOAD</th>
                                    <th class="py-3">DIUPLOAD OLEH</th>
                                    <th class="text-end pe-4 py-3" width="100">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($modul)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-file-pdf fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">Belum ada modul yang diupload.</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php $no=1; foreach($modul as $m): 
                                        $extAdmin = strtolower(pathinfo($m['file_modul'], PATHINFO_EXTENSION));
                                        $isPdfAdmin = ($extAdmin === 'pdf');
                                        $btnColor = $isPdfAdmin ? 'danger' : 'success';
                                        $iconFile = $isPdfAdmin ? 'fa-file-pdf' : 'fa-file-excel';
                                        $textBtn = $isPdfAdmin ? 'Lihat PDF' : 'Lihat Excel';
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= esc($m['judul']) ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('uploads/modul/' . $m['file_modul']) ?>" target="_blank" class="btn btn-sm btn-outline-<?= $btnColor ?> rounded-pill">
                                                <i class="fas <?= $iconFile ?> me-1"></i> <?= $textBtn ?>
                                            </a>
                                        </td>
                                        <td class="text-muted small">
                                            <?= date('d M Y H:i', strtotime($m['created_at'])) ?>
                                        </td>
                                        <td class="text-muted small">
                                            <span class="badge bg-secondary rounded-pill"><?= esc($m['created_by']) ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="<?= base_url('praktikum/delete/' . $m['id']) ?>" class="btn btn-sm btn-light text-danger rounded-circle btn-delete" title="Hapus Modul" onclick="return confirm('Apakah Anda yakin ingin menghapus modul ini?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 bg-light p-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-upload me-2 text-primary"></i> Upload Modul Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUploadModul" action="<?= base_url('praktikum/store') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Judul Modul</label>
                        <input type="text" name="judul" class="form-control form-control-lg rounded-3" placeholder="Contoh: Modul 1 - Rangkaian Listrik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">File Modul</label>
                        <input type="file" id="fileModul" name="file_modul" class="form-control form-control-lg rounded-3" accept=".pdf,.xls,.xlsx" required>
                        <div class="form-text">Format yang diizinkan: PDF atau Excel. Ukuran maksimal 5MB.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-light" style="border-radius: 0 0 1.5rem 1.5rem;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileModul');
    const formUpload = document.getElementById('formUploadModul');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // dalam MB
                if (fileSize > 5) {
                    alert('Gagal! Ukuran file melebihi 5MB. Silakan pilih file yang lebih kecil.');
                    this.value = ''; // Reset file input
                }
            }
        });
    }

    if (formUpload) {
        formUpload.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024;
                if (fileSize > 5) {
                    e.preventDefault();
                    alert('Gagal! Ukuran file melebihi 5MB. Silakan pilih file yang lebih kecil.');
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
