<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
body { background: #f8fafc; }
.pg-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.info-row {
    display: flex;
    align-items: flex-start;
    padding: 13px 0;
    border-bottom: 1px solid #f1f5f9;
    gap: 12px;
}
.info-row:last-child { border-bottom: none; }
.info-label {
    flex: 0 0 170px;
    font-size: .75rem;
    font-weight: 600;
    color: #94a3b8;
    padding-top: 3px;
}
.info-value {
    flex: 1;
    font-size: .88rem;
    color: #1e293b;
    font-weight: 500;
}
.action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: .83rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, transform .1s;
    border: none;
    text-decoration: none;
    width: 100%;
    margin-bottom: 8px;
}
.action-btn:hover { transform: translateY(-1px); }
.action-btn.dark { background: #1e293b; color: #fff; }
.action-btn.dark:hover { background: #0f172a; }
.action-btn.outline { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
.action-btn.outline:hover { background: #f8fafc; }
.action-btn.slate { background: #64748b; color: #fff; }
.action-btn.slate:hover { background: #475569; }
.photo-ring {
    width: 110px; height: 110px;
    border-radius: 50%;
    border: 3px solid #f36c21;
    padding: 3px;
    background: #fff;
    margin: 0 auto 16px;
    position: relative;
}
.photo-ring img {
    width: 100%; height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.photo-controls {
    position: absolute;
    bottom: 0; right: 0;
    display: flex; gap: 4px;
}
.pc-btn {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
}
.role-chip {
    display: inline-block;
    background: #fff7ed;
    color: #c2410c;
    border: 1.5px solid #fed7aa;
    border-radius: 20px;
    padding: 3px 14px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .5px;
}
.stat-box {
    flex: 1;
    text-align: center;
    padding: 12px 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}
.alert-toast {
    border-radius: 10px;
    padding: 12px 18px;
    margin-bottom: 20px;
    font-size: .85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    border: none;
}
</style>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert-toast" style="background:#ecfdf5; color:#065f46;">
    <i class="fas fa-check-circle" style="color:#10b981;"></i>
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
<div class="alert-toast" style="background:#fef2f2; color:#991b1b;">
    <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<div class="row g-4 align-items-start">

    <!-- LEFT: Profile Card -->
    <div class="col-md-4 col-lg-3">
        <div class="pg-card p-4 text-center">

            <!-- Photo -->
            <div class="photo-ring">
                <img id="profilePreviewImg"
                     src="<?= base_url('uploads/profil/' . ($user['foto_profil'] ?? 'default.png')) ?>"
                     alt="Foto Profil"
                     onerror="this.src='https://api.dicebear.com/9.x/initials/svg?seed=<?= urlencode($user['nama_lengkap']) ?>&backgroundColor=1e293b&textColor=ffffff&fontSize=38&fontWeight=700&radius=50'">
                <input type="file" id="fotoInput" accept="image/*" style="display:none;" onchange="openCropModal(event)">
                <div class="photo-controls">
                    <div class="pc-btn" style="background:#f36c21;" onclick="document.getElementById('fotoInput').click()" title="Ganti Foto">
                        <i class="fas fa-camera text-white"></i>
                    </div>
                    <?php if(!empty($user['foto_profil']) && $user['foto_profil'] !== 'default.png'): ?>
                    <a class="pc-btn text-decoration-none" style="background:#ef4444;"
                       href="<?= base_url('profil/deleteFoto') ?>"
                       onclick="return confirm('Hapus foto profil?')" title="Hapus Foto">
                        <i class="fas fa-trash text-white"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Identity -->
            <h6 class="fw-bold mb-1" style="font-size:.98rem; color:#0f172a;"><?= $user['nama_lengkap'] ?></h6>
            <div class="text-muted mb-2" style="font-size:.75rem;"><?= $user['username'] ?></div>
            <div class="role-chip mb-3"><?= strtoupper(str_replace('_', ' ', $user['role'])) ?></div>

            <!-- Email line -->
            <?php if(!empty($user['email'])): ?>
            <div class="small text-muted mb-4" style="font-size:.75rem; word-break:break-all;">
                <i class="fas fa-envelope me-1" style="color:#f36c21;"></i><?= $user['email'] ?>
            </div>
            <?php else: ?>
            <div class="small text-danger fst-italic mb-4" style="font-size:.75rem;">
                <i class="fas fa-exclamation-triangle me-1"></i>Email belum diatur
            </div>
            <?php endif; ?>

            <?php if(session()->get('role') == 'mahasiswa'): ?>
            <!-- Mini stats -->
            <div class="d-flex gap-2 mb-4">
                <div class="stat-box">
                    <div class="fw-bold" style="font-size:1.25rem; color:#f36c21;"><?= $total_transaksi ?? 0 ?></div>
                    <div style="font-size:.62rem; text-transform:uppercase; font-weight:700; color:#94a3b8;">Transaksi</div>
                </div>
                <div class="stat-box">
                    <div class="fw-bold" style="font-size:1.25rem; color:#0f172a;"><?= $sisa_kuota ?? 0 ?></div>
                    <div style="font-size:.62rem; text-transform:uppercase; font-weight:700; color:#94a3b8;">Sisa Kuota</div>
                </div>
            </div>
            <?php endif; ?>

            <div class="border-top pt-3">
                <button class="action-btn dark" data-bs-toggle="modal" data-bs-target="#modalEditProfil">
                    <i class="fas fa-pen" style="font-size:.75rem;"></i> Ubah Profil
                </button>
                <button class="action-btn outline" data-bs-toggle="modal" data-bs-target="#modalPassword">
                    <i class="fas fa-key" style="font-size:.75rem;"></i> Ganti Password
                </button>
                <?php if(session()->get('role') == 'mahasiswa'): ?>
                <a href="<?= base_url('laporan/surat-bebas') ?>" class="action-btn slate">
                    <i class="fas fa-file-pdf" style="font-size:.75rem;"></i> Surat Bebas Lab
                </a>
                <?php endif; ?>
                <a href="<?= base_url('dashboard') ?>" class="action-btn outline" style="margin-bottom:0;">
                    <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- RIGHT: Info Card -->
    <div class="col-md-8 col-lg-9">
        <div class="pg-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-1" style="font-size:1rem; color:#0f172a;">
                        Informasi Akun
                        <button class="btn btn-sm p-0 ms-1 border-0 text-muted" style="font-size:.75rem;" data-bs-toggle="modal" data-bs-target="#modalEditProfil">
                            <i class="fas fa-pen"></i>
                        </button>
                    </h5>
                    <div class="text-muted" style="font-size:.75rem;">Detail identitas dan data pengguna sistem</div>
                </div>
                <span style="background:#ecfdf5; color:#065f46; border:1.5px solid #a7f3d0; border-radius:20px; padding:4px 12px; font-size:.7rem; font-weight:700;">
                    <i class="fas fa-shield-check me-1"></i>Aktif
                </span>
            </div>

            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value"><?= $user['nama_lengkap'] ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Username / NIM</div>
                <div class="info-value"><?= $user['username'] ?></div>
            </div>
            <?php if(!empty($user['npm'])): ?>
            <div class="info-row">
                <div class="info-label">NPM</div>
                <div class="info-value"><?= $user['npm'] ?></div>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">
                    <?php if(!empty($user['email'])): ?>
                        <span style="color:#f36c21;"><?= $user['email'] ?></span>
                    <?php else: ?>
                        <span class="text-danger fst-italic" style="font-size:.82rem;">Belum diatur —
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditProfil" style="color:#f36c21; text-decoration:none; font-weight:600;">Tambahkan</a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Role</div>
                <div class="info-value">
                    <span style="color:#c2410c; font-weight:600;"><?= ucfirst(str_replace('_', ' ', $user['role'])) ?></span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Akun</div>
                <div class="info-value">
                    <span style="background:#ecfdf5; color:#065f46; border-radius:6px; padding:3px 10px; font-size:.75rem; font-weight:600;">
                        <?= ucfirst($user['status_akun'] ?? 'aktif') ?>
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Institusi</div>
                <div class="info-value d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/logo-usk.png') ?>" height="16" onerror="this.style.display='none'">
                    Universitas Syiah Kuala
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Program Studi</div>
                <div class="info-value">Teknik Elektro</div>
            </div>
            <?php if(session()->get('role') == 'mahasiswa'): ?>
            <div class="info-row">
                <div class="info-label">Total Peminjaman</div>
                <div class="info-value fw-semibold" style="color:#f36c21;"><?= $total_transaksi ?? 0 ?> transaksi</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sisa Kuota Pinjam</div>
                <div class="info-value fw-semibold" style="color:#0f172a;"><?= $sisa_kuota ?? 0 ?> / 3 slot tersedia</div>
            </div>
            <?php else: ?>
            <div class="info-row">
                <div class="info-label">Hak Akses</div>
                <div class="info-value">Panel <?= ucfirst(str_replace('_', ' ', $user['role'])) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- MODAL PASSWORD -->
<div class="modal fade" id="modalPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0" style="border-radius:16px; overflow:hidden;">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="fas fa-key me-2" style="color:#f36c21;"></i>Ganti Password</h6>
            </div>
            <div class="modal-body p-4">
                <form action="<?= base_url('profil/changePassword') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Password Saat Ini</label>
                        <input type="password" name="old_password" class="form-control border" style="border-radius:8px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Password Baru</label>
                        <input type="password" name="new_password" class="form-control border" style="border-radius:8px;" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control border" style="border-radius:8px;" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn fw-semibold flex-fill text-white" style="background:#1e293b; border-radius:8px;">Simpan</button>
                        <button type="button" class="btn btn-light border fw-semibold flex-fill" style="border-radius:8px;" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT PROFIL -->
<div class="modal fade" id="modalEditProfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0" style="border-radius:16px; overflow:hidden;">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="fas fa-pen me-2" style="color:#f36c21;"></i>Ubah Data Profil</h6>
            </div>
            <div class="modal-body p-4">
                <form action="<?= base_url('profil/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control border" style="border-radius:8px;" value="<?= $user['nama_lengkap'] ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">Email Aktif</label>
                        <input type="email" name="email" class="form-control border" style="border-radius:8px;" value="<?= $user['email'] ?>" placeholder="nama@example.com" required>
                        <div class="text-muted mt-1" style="font-size:.72rem;">Email digunakan untuk reset password jika lupa.</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn fw-semibold flex-fill text-white" style="background:#1e293b; border-radius:8px;">Simpan</button>
                        <button type="button" class="btn btn-light border fw-semibold flex-fill" style="border-radius:8px;" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CROP MODAL -->
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:16px; overflow:hidden;">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="fas fa-crop-alt me-2" style="color:#f36c21;"></i>Sesuaikan Foto</h6>
                    <div class="text-muted" style="font-size:.73rem;">Geser, zoom, atau putar untuk pas di profil</div>
                </div>
            </div>
            <div class="modal-body p-4">
                <div style="background:#f8fafc; border-radius:10px; max-height:380px; overflow:hidden; display:flex; justify-content:center;">
                    <img id="cropImage" src="" style="max-width:100%; display:block;">
                </div>
                <div class="d-flex gap-2 mt-3 justify-content-center flex-wrap">
                    <button class="btn btn-sm btn-light border" style="border-radius:8px;" onclick="cropperInstance.zoom(0.1)"><i class="fas fa-search-plus me-1"></i>Zoom In</button>
                    <button class="btn btn-sm btn-light border" style="border-radius:8px;" onclick="cropperInstance.zoom(-0.1)"><i class="fas fa-search-minus me-1"></i>Zoom Out</button>
                    <button class="btn btn-sm btn-light border" style="border-radius:8px;" onclick="cropperInstance.rotate(-90)"><i class="fas fa-undo me-1"></i>Putar</button>
                    <button class="btn btn-sm btn-light border" style="border-radius:8px;" onclick="cropperInstance.reset()"><i class="fas fa-sync me-1"></i>Reset</button>
                </div>
            </div>
            <div class="modal-footer border-top p-4 gap-2">
                <button class="btn fw-semibold text-white px-4" style="background:#f36c21; border:none; border-radius:8px;" onclick="saveCroppedPhoto()">
                    <i class="fas fa-check me-2"></i>Gunakan Foto
                </button>
                <button class="btn btn-light border fw-semibold px-4" style="border-radius:8px;" onclick="cancelCrop()">Batal</button>
            </div>
        </div>
    </div>
</div>

<form id="cropUploadForm" action="<?= base_url('profil/uploadFoto') ?>" method="POST" style="display:none;">
    <?= csrf_field() ?>
    <input type="hidden" name="foto_base64" id="foto_base64_input">
</form>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
let cropperInstance = null;
function openCropModal(e) {
    const file = e.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        const img = document.getElementById('cropImage');
        img.src = ev.target.result;
        if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
        new bootstrap.Modal(document.getElementById('cropModal')).show();
        document.getElementById('cropModal').addEventListener('shown.bs.modal', () => {
            cropperInstance = new Cropper(img, { aspectRatio:1, viewMode:1, dragMode:'move', autoCropArea:.85 });
        }, { once: true });
    };
    reader.readAsDataURL(file);
    e.target.value = '';
}
function saveCroppedPhoto() {
    if (!cropperInstance) return;
    const b64 = cropperInstance.getCroppedCanvas({ width:300, height:300, imageSmoothingQuality:'high' }).toDataURL('image/jpeg', 0.88);
    document.getElementById('profilePreviewImg').src = b64;
    document.getElementById('foto_base64_input').value = b64;
    bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
    document.getElementById('cropUploadForm').submit();
}
function cancelCrop() {
    if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
    bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
    document.getElementById('fotoInput').value = '';
}
</script>

<?= $this->endSection() ?>
